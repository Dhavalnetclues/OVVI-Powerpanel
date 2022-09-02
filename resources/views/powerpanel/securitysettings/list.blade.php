@extends('powerpanel.layouts.app')
@section('title')
{{Config::get('Constant.SITE_NAME')}} - PowerPanel
@stop
@section('css')
<link href="{{ $CDN_PATH.'resources/global/plugins/datatables/datatables.min.css' }}" rel="stylesheet" type="text/css" />
<link href="{{ $CDN_PATH.'resources/global/plugins/datatables/plugins/bootstrap/datatables.bootstrap.css' }}" rel="stylesheet" type="text/css" />
<link href="{{ $CDN_PATH.'resources/global/plugins/highslide/highslide.css' }}" rel="stylesheet" type="text/css" />
<!-- <link href="{{ $CDN_PATH.'resources/global/css/rank-button.css' }}" rel="stylesheet" type="text/css" /> -->
@endsection
@section('content')
{{-- @include('powerpanel.partials.breadcrumbs') --}}
{!! csrf_field() !!}
<div class="row">
    @php
    $logo_url = $CDN_PATH.'assets/images/signin_scene.png';
    $userid = auth()->user()->id;
    $SecurityUser = \App\User::getRecordById($userid);
    if($SecurityUser['chrAuthentication']=="Y"){
    $check_2_Step ="checked";
    $style="block";
    $style1="none";
    }else{
    $check_2_Step="";
    $style="none";
    $style1="block";
    }
    @endphp
    <div class="col-lg-12">
        <div class="card">
            <div class="card-body p-30">
                <div class="portlet box green_dark security_box">
                    <h4 class="card-title mb-4">Signing in to {{ Config::get('Constant.SITE_NAME') }}</h4>
                    {{-- <div class="img-security img_signing">
                        <img src="{{$logo_url}}">
                    </div> --}}   
                    <div class="security-psw-lst clearfix">  
                        <div class="row">
                            <div class="col-lg-6 col-sm-12">                    
                                <div class="security-psw mb-2"> 
                                    <div class="text-uppercase fw-medium text-muted">Password</div>   
                                    <div class="fs-15">Last changed {{ $PasswordLastchanged }}</div>
                                </div>
                            </div>
                            @if (Config::get('Constant.DEFAULT_AUTHENTICATION') == 'Y')
                            <div class="col-lg-6 col-sm-12">                    
                                <div class="security-psw">
                                    <div class="text-uppercase fw-medium d-inline-block me-3">2-Step Verification</span></div>
                                    <label class="switch_toogle">
                                        <input type="checkbox" {{$check_2_Step}} name="2_Step" id="2_Step" onclick="check2Step()">
                                        <span class="slider_span"></span>
                                    </label>                                                   
                                </div>
                                <div class="security-psw-email" id="verify" style="display:{{$style}};">
                                    {!! Form::open(['method' => 'post','id'=>'frmEmailverify','name'=>'frmEmailverify']) !!}
                                    <div class="row mt-3">
                                        <div class="col-lg-12">
                                            <div class="input-group cm-floating mb-2">
                                                <input type="text" class="form-control" placeholder="Email" value="{{$paremail}}" id="verifyEmail" name="verifyEmail" disabled="">
                                                <label class="form-label">Email<span aria-required="true" class="required"> * </span></label>
                                            </div>
                                            <a class="btn btn-secondary" href="JavaScript:void(0);" onclick="EditEmail()" id="Edit" style="display:{{$style1}};">Edit</a>
                                            <span class="form-group" id="email_submit" style="display: none;">
                                                <button type="submit" class="btn btn-primary" title="Submit" id="Email_submit">Submit</button>
                                            </span>
                                        </div> 
                                    </div>
                                    {!! Form::close() !!}
                                    <div id="otp_div" style="display: none;">
                                        {!! Form::open(['method' => 'post','id'=>'frmOtpverify','name'=>'frmOtpverify']) !!}
                                        <div class="row mt-2">
                                            <div class="col-lg-12">
                                                <div class="input-group">
                                                    <input type="text" class="form-control" placeholder="OTP*" id="otp" name="otp" maxlength="6" minlength="6" onkeypress="javascript: return KeycheckOnlyRendom(event);">
                                                    <button type="submit" class="btn btn-primary" title="Verify" id="Otp_Verify">Verify</button>
                                                </div>
                                            </div>
                                        </div>
                                        {!! Form::close() !!}
                                    </div>                                    
                                </div>
                            </div>
                            @endif
                        </div> 
                    </div>
                    @if (Config::get('Constant.DEFAULT_AUTHENTICATION') == 'Y')
                    <p class="mt-2 mb-0"><strong>Note:</strong> If you activate 2-Step Verification, you can't login into the PowerPanel without the access code which you will be received on your personal email address at the time of Login.</p>
                    @endif
                </div>
            </div>
        </div>
        @php 
        if($SecurityUser['chrSecurityQuestions']=="Y"){
        $check ="checked";	
        $display ="block";	
        }else{
        $check="";
        $display="none";
        }
        @endphp
        <div class="card">
            <div class="card-body p-30">
                <div class="portlet box green_dark security_box">
                    <div class="d-flex justify-content-between">
                        <h4 class="card-title mb-0">Security Questions</h4>
                        <div class="caption-switch">                    
                            <label class="switch_toogle">
                                <input type="checkbox" {{$check}} onclick="checkFluency()" name="Ans_id" id="Ans_id">
                                <span class="slider_span"></span>
                            </label>
                        </div>                               
                    </div>
                    <div class="successMSG"></div>
                    <div class="portlet-body form_pattern" id="myDIV" style="display: {{$display}};">                
                        <div class="form-body mt-4">
                            {!! Form::open(['method' => 'post','id'=>'frmSecurityQuestions','name'=>'frmSecurityQuestions']) !!}
                            @php
                            $SecurityQuestion = \App\User::GetSecurityQuestion();
                            @endphp
                            <div class="row">
                                <div class="col-lg-6 col-sm-12">
                                    <div class="form-group @if($errors->first('Question1')) has-error @endif form-md-line-input cm-floating">
                                        <label class="form_title" for="title">Question1<span aria-required="true" class="required"> * </span></label>
                                        <select class="form-select" name="Question1" id="Question1">
                                            <option value="">Select Question1</option>
                                            @foreach($SecurityQuestion as $Question)
                                            @php
                                            if($SecurityUser['varQuestion1'] == $Question->id){
                                            $select1 = 'selected';
                                            }else{
                                            $select1 = '';
                                            }
                                            if($SecurityUser['varQuestion2'] == $Question->id){
                                            $disabled1 = 'disabled';
                                            }else if($SecurityUser['varQuestion3'] == $Question->id){
                                            $disabled1 = 'disabled';
                                            }else{
                                            $disabled1 = '';
                                            }
                                            @endphp
                                            <option value="{{$Question->id}}" {{$select1}} {{$disabled1}}>{{$Question->var_questions}}</option>
                                            @endforeach
                                        </select>
                                        <span class="help-block">
                                            {{ $errors->first('Question1') }}
                                        </span>
                                    </div>
                                </div>
                                <div class="col-lg-6 col-sm-12">
                                    <div class="form-group form-md-line-input cm-floating">
                                        <label class="form_title" for="site_name">Answer1 <span aria-required="true" class="required"> * </span></label>
                                        {!! Form::text('Answer1', isset($SecurityUser['varAnswer1']) ? $SecurityUser['varAnswer1']:old('Answer1'), array('maxlength'=>'50','id'=>'Answer1','placeholder' => 'Answer1','class' => 'form-control maxlength-handler','autocomplete'=>'off')) !!}
                                        <span class="help-block">
                                            {{ $errors->first('Answer1') }}
                                        </span>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-lg-6 col-sm-12">
                                    <div class="form-group form-md-line-input cm-floating">
                                        <label class="form_title" for="title">Question2<span aria-required="true" class="required"> * </span></label>
                                        <select class="form-select" name="Question2" id="Question2">
                                            <option value="">Select Question2</option>
                                            @foreach($SecurityQuestion as $Question)
                                            @php
                                            if($SecurityUser['varQuestion2'] == $Question->id){
                                            $select2 = 'selected';
                                            }else{
                                            $select2 = '';
                                            }
                                            if($SecurityUser['varQuestion1'] == $Question->id){
                                            $disabled2 = 'disabled';
                                            }else if($SecurityUser['varQuestion3'] == $Question->id){
                                            $disabled2 = 'disabled';
                                            }else{
                                            $disabled2 = '';
                                            }
                                            @endphp
                                            <option value="{{$Question->id}}" {{$select2}} {{$disabled2}}>{{$Question->var_questions}}</option>
                                            @endforeach
                                        </select>
                                        <span class="help-block">
                                            {{ $errors->first('Question2') }}
                                        </span>
                                    </div>
                                </div>
                                <div class="col-lg-6 col-sm-12">
                                    <div class="form-group form-md-line-input cm-floating">
                                        <label class="form_title" for="site_name">Answer2 <span aria-required="true" class="required"> * </span></label>
                                        {!! Form::text('Answer2', isset($SecurityUser['varAnswer2']) ? $SecurityUser['varAnswer2']:old('Answer2'), array('maxlength'=>'50','id'=>'Answer2','placeholder' => 'Answer2','class' => 'form-control maxlength-handler','autocomplete'=>'off')) !!}
                                        <span class="help-block">
                                            {{ $errors->first('Answer2') }}
                                        </span>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-lg-6 col-sm-12">
                                    <div class="form-group form-md-line-input cm-floating">
                                        <label class="form_title" for="title">Question3<span aria-required="true" class="required"> * </span></label>
                                        <select class="form-select" name="Question3" id="Question3">
                                            <option value="">Select Question3</option>
                                            @foreach($SecurityQuestion as $Question)
                                            @php
                                            if($SecurityUser['varQuestion3'] == $Question->id){
                                            $select3 = 'selected';
                                            }else{
                                            $select3 = '';
                                            }
                                            if($SecurityUser['varQuestion1'] == $Question->id){
                                            $disabled3 = 'disabled';
                                            }else if($SecurityUser['varQuestion2'] == $Question->id){
                                            $disabled3 = 'disabled';
                                            }else{
                                            $disabled3 = '';
                                            }
                                            @endphp
                                            <option value="{{$Question->id}}" {{$select3}} {{$disabled3}}>{{$Question->var_questions}}</option>
                                            @endforeach
                                        </select>
                                        <span class="help-block">
                                            {{ $errors->first('Question3') }}
                                        </span>
                                    </div>
                                </div>
                                <div class="col-lg-6 col-sm-12">
                                    <div class="form-group form-md-line-input cm-floating">
                                        <label class="form_title" for="site_name">Answer3 <span aria-required="true" class="required"> * </span></label>
                                        {!! Form::text('Answer3', isset($SecurityUser['varAnswer3']) ? $SecurityUser['varAnswer3']:old('Answer3'), array('maxlength'=>'50','id'=>'Answer3','placeholder' => 'Answer3','class' => 'form-control maxlength-handler','autocomplete'=>'off')) !!}
                                        <span class="help-block">
                                            {{ $errors->first('Answer3') }}
                                        </span>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group month-input">
                                <label class="form_title">When to ask the question?</label>
                                <div class="row">
                                    <div class="col-sm-12">
                                        <div class="form-group mb-3">
                                            @if(isset($SecurityUser->intSearchRank))
                                            @php $srank = $SecurityUser->intSearchRank; @endphp
                                            @else
                                            @php
                                            $srank = null !== old('search_rank') ? old('search_rank') : 2 ;
                                            @endphp
                                            @endif
                                            <div class="search_rank"> <!-- wrapper #class remove -->
                                                <div class="form-check form-check-inline">
                                                    <input type="radio" class="form-check-input" value="1" name="search_rank" @if($srank == 1) checked @endif id="yes_radio">
                                                    <label class="form-check-label" for="yes_radio" id="yes-lbl">High</label>
                                                </div>
                                                <div class="form-check form-check-inline">
                                                    <input type="radio" class="form-check-input" value="2" name="search_rank" @if($srank == 2) checked @endif id="maybe_radio">
                                                    <label class="form-check-label" for="maybe_radio" id="maybe-lbl">Medium</label>
                                                </div>
                                                <div class="form-check form-check-inline">
                                                    <input type="radio" class="form-check-input" value="3" name="search_rank" @if($srank == 3) checked @endif id="no_radio">
                                                    <label class="form-check-label" for="no_radio" id="no-lbl">Low</label>
                                                </div>
                                                <!-- <div class="toggle"></div> -->
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-sm-12">
                                        <div class="form-actions mt-2">                        
                                            <button id="Questions_submit" type="submit" name="Submit" class="btn btn-primary bg-gradient waves-effect waves-light btn-label" value="Submit">
                                                <div class="d-flex">
                                                    <div class="flex-shrink-0"><i class="ri-send-plane-line label-icon align-middle fs-20 me-2"></i></div>
                                                    <div class="flex-grow-1">Submit</div>
                                                </div>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            {!! Form::close() !!}
                        </div>    
                    </div>    
                </div>
            </div>
        </div>
        <div class="clearfix"></div>
        <div class="row">
            <div class="col-lg-6 col-sm-12 mb-3">
                <div class="card portlet box green_dark security_box h-100">
                    <div class="card-header portlet-title">
                        <h4 class="card-title mb-0">Your Devices</h4>
                        <p class="mt-2 mb-0">You're currently signed in to your {{ Config::get('Constant.SITE_NAME') }} Account on these devices</p>
                    </div>
                    <div class="card-body portlet-body pb-0">    
                        <div class="divices-box">
                            @php
                            $userid = auth()->user()->id;
                            $Device = \App\LoginLog::getcurrently_DevicesUser($userid);
                            @endphp
                            @foreach($Device as $row)
                            @php 
                            if($row->varDevice=="Desktop"){
                            $Icon ="ri-computer-line";	
                            }else{
                            $Icon ="ri-device-line";
                            }
                            $startDate = date('' . Config::get('Constant.DEFAULT_DATE_FORMAT') . ' ' . Config::get('Constant.DEFAULT_TIME_FORMAT') . '', strtotime($row['created_at']));
                            $logid = Session::get('loghistory_id');
                            @endphp
                            <div class="divices-div border rounded border-dashed p-2 mb-3">
                                <div class="d-flex align-items-center">
                                    <div class="flex-shrink-0 me-3">
                                        <div class="avatar-sm">
                                            <div class="avatar-title bg-light text-primary rounded fs-24">
                                                <i class="{{$Icon}}"></i>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="flex-grow-1 overflow-hidden">
                                        <h5 class="fs-14 mb-1">{{ $row->varBrowser_Platform }}</h5>
                                        <p class="mb-0">{{ $row->varCity }}, {{ $row->varState_prov }}, {{ $row->varCountry_name }} - {{$startDate}}</p>
                                        @if($row->id==$logid)
                                        <div class="iintNd mt-2">
                                            <div class="xoXYwe oGaYYd">
                                                {{-- <div class="YaVKnd" style="display:inline-block">
                                                    <style nonce="">.HJOYVc37 {width: 20px; height: 20px;}</style>
                                                    <figure class="HJOYV HJOYVc37" aria-hidden="true">
                                                        <img class="YPzqGd" src="https://www.gstatic.com/identity/boq/accountsettingsmobile/status_on_20x20_f22600d4702c742d962e5e06c7807f5e.png" srcset="https://www.gstatic.com/identity/boq/accountsettingsmobile/status_on_40x40_b77c748406dd88d1ecdff170e06fb7bd.png 2x, https://www.gstatic.com/identity/boq/accountsettingsmobile/status_on_60x60_7c20df9338944ad9cf90cb4a86ab4c40.png 3x, https://www.gstatic.com/identity/boq/accountsettingsmobile/status_on_80x80_9d037b1ed1dcb028ba487f77e3eedff7.png 4x">
                                                    </figure>
                                                </div> --}}
                                                <div class="kFNik"><i class="ri-checkbox-circle-fill fs-18 text-success"></i> This device</div>
                                            </div>
                                        </div>
                                        @endif
                                    </div>
                                </div>
                            </div>
                            @endforeach
                        </div>        
                    </div>    
                </div>
            </div>
            <div class="col-lg-6 col-sm-12 mb-3">
                <div class="card portlet box green_dark security_box h-100">
                    <div class="card-header portlet-title">
                        <h4 class="card-title mb-0">Previous Devices</h4> 
                        <p class="mt-2 mb-0">You're previous signed in to your {{ Config::get('Constant.SITE_NAME') }} Account on these devices</p>               
                    </div>
                    <div class="card-body portlet-body pb-0">                
                        <div class="divices-box">
                            @php
                            $userid = auth()->user()->id;
                            $Device = \App\LoginLog::getprevious_DevicesUser($userid);
                            @endphp
                            @foreach($Device as $row)
                            @php 
                            if($row->varDevice=="Desktop"){
                            $Icon ="ri-computer-line";	
                            }else{
                            $Icon ="ri-device-line";
                            }
                            $startDate = date('' . Config::get('Constant.DEFAULT_DATE_FORMAT') . ' ' . Config::get('Constant.DEFAULT_TIME_FORMAT') . '', strtotime($row['created_at']));
                            $logid = Session::get('loghistory_id');
                            @endphp
                            <div class="divices-div border rounded border-dashed p-2 mb-2">
                                <div class="d-flex align-items-center">
                                    <div class="flex-shrink-0 me-3">
                                        <div class="avatar-sm">
                                            <div class="avatar-title bg-light text-primary rounded fs-24">
                                                <i class="{{$Icon}}"></i>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="flex-grow-1 overflow-hidden">
                                        <h5 class="fs-14 mb-1">{{ $row->varBrowser_Platform }}</h5>
                                        <p class="mb-0">{{ $row->varCity }}, {{ $row->varState_prov }}, {{ $row->varCountry_name }} - {{$startDate}}</p>
                                        @if($row->id==$logid)
                                        <div class="iintNd mt-2">
                                            <div class="xoXYwe oGaYYd">
                                                {{-- <div class="YaVKnd" style="display:inline-block">
                                                    <style nonce="">.HJOYVc37 {width: 20px; height: 20px;}</style>
                                                    <figure class="HJOYV HJOYVc37" aria-hidden="true">
                                                        <img class="YPzqGd" src="https://www.gstatic.com/identity/boq/accountsettingsmobile/status_on_20x20_f22600d4702c742d962e5e06c7807f5e.png" srcset="https://www.gstatic.com/identity/boq/accountsettingsmobile/status_on_40x40_b77c748406dd88d1ecdff170e06fb7bd.png 2x, https://www.gstatic.com/identity/boq/accountsettingsmobile/status_on_60x60_7c20df9338944ad9cf90cb4a86ab4c40.png 3x, https://www.gstatic.com/identity/boq/accountsettingsmobile/status_on_80x80_9d037b1ed1dcb028ba487f77e3eedff7.png 4x">
                                                    </figure>
                                                </div> --}}
                                                <div class="kFNik"><i class="ri-checkbox-circle-fill fs-18 text-success"></i> This device</div>
                                            </div>
                                        </div>
                                        @endif
                                    </div>
                                </div>
                            </div>
                            @endforeach
                        </div>        
                    </div>    
                </div>
            </div>
        </div>
    </div>    
</div>
<!-- /.modal -->
@endsection
@section('scripts')
<script type="text/javascript">
    window.site_url = '{!! url("/") !!}';
    var MODULE_URL = '{!! url("/powerpanel/live-user") !!}';</script>
<script src="{{ $CDN_PATH.'resources/global/plugins/jquery-cookie-master/src/jquery.cookie.js' }}" type="text/javascript"></script>	
<script src="{{ $CDN_PATH.'resources/global/plugins/datatables/datatables.min.js' }}" type="text/javascript"></script>
<script src="{{ $CDN_PATH.'resources/global/scripts/datatable.js' }}" type="text/javascript"></script>
<script src="{{ $CDN_PATH.'resources/global/plugins/datatables/plugins/bootstrap/datatables.bootstrap.js' }}" type="text/javascript"></script>
<script src="{{ $CDN_PATH.'resources/global/plugins/bootstrap-datepicker/js/bootstrap-datepicker.min.js' }}" type="text/javascript"></script>
<script src="{{ $CDN_PATH.'resources/global/plugins/bootstrap-maxlength/bootstrap-maxlength.min.js' }}" type="text/javascript"></script>
<script src="{{ $CDN_PATH.'resources/pages/scripts/security-settings-datatables-ajax.js' }}" type="text/javascript"></script>	
<script src="{{ $CDN_PATH.'resources/pages/scripts/custom.js' }}" type="text/javascript"></script>
<script src="{{ $CDN_PATH.'resources/pages/scripts/numbervalidation.js' }}" type="text/javascript"></script>
<script src="{{ $CDN_PATH.'resources/global/plugins/highslide/highslide-with-html.js' }}" type="text/javascript"></script>
<script>
    var Security_URL = window.site_url + "/powerpanel/users/Security_Remove";
    var Security_URL_Add = window.site_url + "/powerpanel/users/Security_Add";
    function checkFluency()
    {
        var checkbox = document.getElementById('Ans_id');
        if (checkbox.checked != true)
        {
            $.ajax({
                type: 'POST',
                url: Security_URL,
                data: 'id=' + checkbox,
                success: function () {
                    $("#Question1").val("");
                    $("#Question2").val("");
                    $("#Question3").val("");
                    $("#Answer1").val("");
                    $("#Answer2").val("");
                    $("#Answer3").val("");
                    document.getElementById("myDIV").style.display = "none";
                }
            });
        } else {
            document.getElementById("myDIV").style.display = "block";
        }
    }
    var step_URL_Email_Otp = window.site_url + "/powerpanel/users/step_Email_Otp";
    var step_URL_Otp_verify = window.site_url + "/powerpanel/users/step_otp_verify";
    function EditEmail() {
        $('#verifyEmail').val("");
        $("#verifyEmail").removeAttr("disabled");
        $("#Edit").attr("style", "display:none");
    }
    function check2Step()
    {
        var checkbox_2_Step = document.getElementById('2_Step');
        var Authentication = "{{$SecurityUser['chrAuthentication']}}";
        var paremail = "{{$paremail}}";
        $("#verifyEmail").val(paremail);
        $("#verifyEmail").attr("disabled", "disabled");
        $("#verifyEmail-error").hide();
        $("div").removeClass("has-error");
        if (Authentication == 'N') {
            if (checkbox_2_Step.checked == true) {
                $("#verify").show();
                $("#email_submit").show();
                $("#Edit").show();
            } else {
                $("#verify").hide();
                $("#email_submit").hide();
                $("#Edit").hide();
                $("#otp_div").hide();
            }
        } else if (Authentication == 'Y') {
            if (checkbox_2_Step.checked == false) {
                $("#verify").show();
                $("#email_submit").show();
                $("#Edit").show();
            } else {
                $("#email_submit").hide();
                $("#Edit").hide();
                $("#otp_div").hide();
            }
        }
//            $("#2_Step").attr("disabled", "disabled");
    }
</script>
@endsection