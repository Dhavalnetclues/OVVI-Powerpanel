@extends('powerpanel.layouts.app')
@section('title')
    {{ Config::get('Constant.SITE_NAME') }} - PowerPanel
@endsection
@section('content')
@php $settings = json_decode(Config::get("Constant.MODULE.SETTINGS")); @endphp
<style type="text/css">
    .removePhone,
    .removeEmail,
    .removePhone:hover,
    .removeEmail:hover {
        color: #e73d4a;
    }
</style>

<div class="row">
    <div class="col-md-12 settings">
        @if(Session::has('message'))
        <div class="alert alert-success">
            {{ Session::get('message') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
        @endif
        
        <div class="live-preview">
            {!! Form::open(['method' => 'post', 'id' => 'frmContactUS']) !!}
                <div class="card">
                    <div class="card-body p-30">
                        <div class="row">
                            {{-- Name --}}
                            <div class="col-md-12">
                                <div class="{{ $errors->has('name') ? 'has-error' : '' }} form-md-line-input cm-floating">
                                    <label class="form-label" for="name">
                                        {{ trans('contactinfo::template.common.name') }} <span aria-required="true" class="required"> * </span>
                                    </label>
                                    {!! Form::text('name', isset($contactInfo->varTitle) ? $contactInfo->varTitle : old('name'), ['class' => 'form-control input-sm maxlength-handler', 'maxlength' => '150', 'id' => 'name', 'autocomplete' => 'off']) !!}
                                    <span class="help-block">{{ $errors->first('name') }}</span>
                                </div>
                            </div>
                            {{-- Email --}}
                            <div class="col-md-12">
                                <div class="{{ $errors->has('email') ? 'has-error' : '' }} form-md-line-input cm-floating">
                                    <label class="form-label" for="email">
                                        {{ trans('contactinfo::template.common.email') }} <span aria-required="true" class="required"> * </span>
                                    </label>
                                    {!! Form::text('email', isset($contactInfo->varEmail) ? $contactInfo->varEmail : old('email'), ['class' => 'form-control input-sm', 'maxlength' => '100', 'id' => 'email', 'autocomplete' => 'off']) !!}
                                    <span class="help-block">{{ $errors->first('email') }}</span>
                                </div>
                            </div>
                            {{-- Phone No --}}
                            <div class="col-md-12">
                                <div class="{{ $errors->has('phone_no') ? 'has-error' : '' }} form-md-line-input cm-floating">
                                    <label class="form-label" for="phone_no">{{ trans('contactinfo::template.common.phoneno') }}</label>
                                    {!! Form::text('phone_no', isset($contactInfo->varPhoneNo) ? $contactInfo->varPhoneNo : old('phone_no'), ['class' => 'form-control input-sm', 'id' => 'phone_no', 'autocomplete' => 'off']) !!}
                                    <span class="help-block">{{ $errors->first('phone_no') }}</span>
                                </div>
                            </div>
                            {{-- FAX --}}
                            <div class="col-md-12">
                                <div class="phoneField {{ $errors->has('fax') ? 'has-error' : '' }} form-md-line-input cm-floating">
                                    <label class="form-label" for="faxZ">{{ trans('contactinfo::template.common.fax') }} </label>
                                    {!! Form::text('fax', isset($contactInfo->varFax) ? $contactInfo->varFax : old('fax'), ['class' => 'form-control input-sm', 'id' => 'fax', 'autocomplete' => 'off', 'maxlength' => '20', 'onpaste' => 'return false']) !!}
                                    <span class="help-block">{{ $errors->first('fax') }}</span>
                                </div>
                            </div>
                            {{-- Description --}}
                            <div class="col-md-12">
                                <div class="phoneField {{ $errors->has('description') ? 'has-error' : '' }} form-md-line-input cm-floating">
                                    <label class="form-label" for="description">Working Hours </label>
                                    {!! Form::text('description', isset($contactInfo->txtDescription) ? $contactInfo->txtDescription : old('description'), ['class' => 'form-control input-sm', 'id' => 'description', 'autocomplete' => 'off']) !!}
                                    <span class="help-block">{{ $errors->first('description') }}</span>
                                </div>
                            </div>
                            {{-- Address --}}
                            <div class="col-md-12">
                                <div class="form-md-line-input cm-floating @if ($errors->first('address')) has-error @endif">
                                    <label class="form-label" for="address">{{ trans('contactinfo::template.common.address') }}</label>
                                    {!! Form::textarea('address', isset($contactInfo->txtAddress) ? $contactInfo->txtAddress : old('address'), ['class' => 'form-control', 'maxlength' => '250', 'id' => 'address', 'style' => 'max-height:80px;']) !!}
                                    <span class="help-block">{{ $errors->first('address') }}</span>
                                </div>
                            </div>
                            {{-- Mailing Address --}}
                            <div class="col-md-12">
                                <div class="form-md-line-input cm-floating @if ($errors->first('address')) has-error @endif">
                                    <label class="form-label" for="address">Mailing Address</label>
                                    {!! Form::textarea('mailingaddress', isset($contactInfo->mailingaddress) ? $contactInfo->mailingaddress : old('mailingaddress'), ['class' => 'form-control', 'maxlength' => '250', 'id' => 'mailingaddress', 'style' => 'max-height:80px;']) !!}
                                    <span class="help-block">{{ $errors->first('mailingaddress') }}</span>
                                </div>
                            </div>                            
                        </div>
                    </div>
                </div>
                <div class="card">
                    <div class="card-body p-30">
                        <div class="row">
                            {{-- Contact Info --}}
                            <div class="col-lg-6 col-sm-12 mb-30">
                                <div class="{{ $errors->has('primary') ? ' has-error' : '' }} ">
                                    <h4 class="form-section mb-3">
                                        {{ trans('contactinfo::template.common.primary') }} <span aria-required="true" class="required"> * </span>
                                    </h4>
                                    <div class="md-radio-inline">
                                        <div class="form-check form-check-inline">
                                            @if ((isset($contactInfo->chrIsPrimary) && $contactInfo->chrIsPrimary == 'Y') || (null == Request::old('primary') || Request::old('primary') == 'Y'))
                                                @php $checked_yes = 'checked' @endphp
                                            @else
                                                @php $checked_yes = '' @endphp
                                            @endif
                                            <input class="form-check-input" {{ $checked_yes }} type="radio" name="primary" id="radio6" value="Y">
                                            <label class="form-check-label" for="chrMenuDisplay"> 
                                                <span class="check"></span> <span class="box"></span> Yes 
                                            </label>
                                        </div>
                                        <div class="form-check form-check-inline">
                                            @if ((isset($contactInfo->chrIsPrimary) && $contactInfo->chrIsPrimary == 'N') || old('primary') == 'N')
                                                @php $checked_no = 'checked' @endphp
                                            @else
                                                @php $checked_no = '' @endphp
                                            @endif
                                            <input class="form-check-input" {{ $checked_no }} type="radio" name="primary" id="radio7" value="N">
                                            <label class="form-check-label" for="chrMenuDisplay"> 
                                                <span class="check"></span> <span class="box"></span> No 
                                            </label>
                                        </div>
                                    </div>
                                    <span class="help-block">{{ $errors->first('primary') }}</span>
                                </div>
                            </div>
                            {{-- Display Info --}}
                            <div class="col-lg-6 col-sm-12 m-30">
                                <h4 class="form-section mb-3">
                                    Display Information
                                </h4>
                                @include('powerpanel.partials.displayInfo',['display'=> isset($contactInfo->chrPublish) ? $contactInfo->chrPublish:null])
                            </div>
                            {{-- @if (isset($contactInfo))
                                <div class="multi-email">
                                    @php
                                        $emcnt=0;
                                        $selectedEmail=unserialize($contactInfo->varEmail);
                                    @endphp
                                    @if (count($selectedEmail) > 1)
                                        @foreach ($selectedEmail as $email)
                                            <div class="emailField {{ $errors->has('email') ? 'has-error' : '' }} form-md-line-input">
                                                <label class="form-label" for="email">{{ trans('contactinfo::template.common.email') }} @if ($emcnt == 0)<span aria-required="true" class="required"> * </span>@endif</label>
                                                {!! Form::text('email['.($emcnt).']',$email, array('class' => 'form-control input-sm', 'placeholder'=>'Email', 'maxlength'=>'100','id' => 'email'.($emcnt),'autocomplete'=>'off')) !!}
                                                @if ($emcnt == 0)
                                                    <!--<a href="javascript:void(0);" class="addMoreEmail add_more" title="Add More"><i class="fa fa-plus"></i> Add More</a>-->
                                                @else
                                                    <a href="javascript:void(0);" class="removeEmail add_more" title="Remove"><i class="ri-delete-bin-line"></i> Remove</a>
                                                @endif
                                                <span class="help-block">{{ $errors->first('email['.($emcnt).']') }}</span>
                                            </div>
                                            @php if( $emcnt < count($selectedEmail)-1){ $emcnt++; } @endphp
                                        @endforeach
                                    @else
                                        <div class="emailField {{ $errors->has('email') ? 'has-error' : '' }} form-md-line-input">
                                            <label class="form-label" for="email">{{ trans('contactinfo::template.common.email') }} @if ($emcnt == 0)<span aria-required="true" class="required"> * </span>@endif</label>
                                            {!! Form::text('email['.($emcnt).']',$selectedEmail[0], array('class' => 'form-control input-sm', 'placeholder'=>'Email', 'maxlength'=>'100','id' => 'email'.($emcnt),'autocomplete'=>'off')) !!}
                                            @if ($emcnt == 0)
                                                <!--<a href="javascript:void(0);" class="addMoreEmail add_more" title="Add More"><i class="fa fa-plus"></i> Add More</a>-->
                                            @else
                                                <a href="javascript:void(0);" class="removeEmail add_more" title="Remove"><i class="ri-delete-bin-line"></i> Remove</a>
                                            @endif
                                            <span class="help-block">{{ $errors->first('email['.($emcnt).']') }}</span>
                                        </div>
                                    @endif
                                </div>
                                <div class="multi-phone">
                                    @php
                                        $phcnt=0;
                                        $selectedPhone = unserialize($contactInfo->varPhoneNo);
                                    @endphp
                                    @if (count($selectedPhone) > 1)
                                        @foreach ($selectedPhone as $key => $phone)
                                        <div class="phoneField {{ $errors->has('phone_no') ? 'has-error' : '' }} form-md-line-input">
                                            <label class="form-label" for="phone_no">{{ trans('contactinfo::template.common.phoneno') }} @if ($phcnt == 0)@endif</label>
                                            {!! Form::text('phone_no['.($phcnt).']',$phone, array('class' => 'form-control input-sm','id' => 'phone_no'.($phcnt),'placeholder' => 'Phone No','autocomplete'=>'off','maxlength'=>"20", 'onkeypress'=>"javascript: return KeycheckOnlyPhonenumber(event);",'onpaste'=>'return false')) !!}
                                            @if ($phcnt == 0)
                                            <!--<a href="javascript:void(0);" class="addMorePhone add_more" title="Add More"><i class="fa fa-plus"></i> Add More</a>-->
                                            @else
                                                <a href="javascript:void(0);" class="removePhone add_more" title="Remove"><i class="ri-delete-bin-line"></i> Remove</a>
                                            @endif
                                            <span class="help-block">{{ $errors->first('phone_no') }}</span>
                                        </div>
                                        @php if( $phcnt < count($selectedPhone)-1){ $phcnt++; }  @endphp
                                    @endforeach
                                    @else
                                        <div class="phoneField {{ $errors->has('phone_no') ? 'has-error' : '' }} form-md-line-input">
                                            <label class="form-label" for="phone_no">{{ trans('contactinfo::template.common.phoneno') }} @if ($phcnt == 0)@endif</label>
                                            {!! Form::text('phone_no['.($phcnt).']',$selectedPhone[0], array('class' => 'form-control input-sm','id' => 'phone_no'.($phcnt),'placeholder' => 'Phone No','autocomplete'=>'off','maxlength'=>"20", 'onkeypress'=>"javascript: return KeycheckOnlyPhonenumber(event);",'onpaste'=>'return false')) !!}
                                            @if ($phcnt == 0)
                                                <!--<a href="javascript:void(0);" class="addMorePhone add_more" title="Add More"><i class="fa fa-plus"></i> Add More</a>-->
                                            @else
                                                <a href="javascript:void(0);" class="removePhone add_more" title="Remove"><i class="ri-delete-bin-line"></i> Remove</a>
                                            @endif
                                            <span class="help-block">{{ $errors->first('phone_no') }}</span>
                                        </div>
                                    @endif
                                </div>
                                @else
                                <div class="multi-email">
                                    <div class="emailField {{ $errors->has('email') ? 'has-error' : '' }} form-md-line-input">
                                        <label class="form-label" for="email[0]">{{ trans('contactinfo::template.common.email') }}<span aria-required="true" class="required"> * </span></label>
                                        {!! Form::text('email[0]', Request::old('email'), array('class' => 'form-control input-sm email', 'maxlength'=>'100','id' => 'email0','placeholder' => 'Email','autocomplete'=>'off')) !!}
                                        <!--<a href="javascript:void(0);" class="addMoreEmail add_more" title="Add More"><i class="fa fa-plus"></i> Add More</a>-->
                                        <span class="help-block">{{ $errors->first('email') }}</span>
                                    </div>
                                </div>
                                <div class="multi-phone">
                                    <div class="phoneField {{ $errors->has('phone_no') ? 'has-error' : '' }} form-md-line-input">
                                        <label class="form-label" for="phone_no[0]">{{ trans('contactinfo::template.common.phoneno') }}</label>
                                        {!! Form::text('phone_no[0]', Request::old('phone_no'), array('class' => 'form-control input-sm','id' => 'phone_no0','placeholder' => 'Phone No','autocomplete'=>'off', 'maxlength'=>"20", 'onkeypress'=>"javascript: return KeycheckOnlyPhonenumber(event);",'onpaste'=>'return false')) !!}
                                        <!--<a href="javascript:void(0);" class="addMorePhone add_more" title="Add More"><i class="fa fa-plus"></i> Add More</a>-->
                                        <span class="help-block">{{ $errors->first('phone_no') }}</span>
                                    </div>
                                </div>
                            @endif --}}
                            {{-- <div class="row">
                                <div class="col-md-12">
                                    <div class="@if ($errors->first('order')) has-error @endif form-md-line-input">
                                        {!! Form::text('order', isset($contactInfo->intDisplayOrder)?$contactInfo->intDisplayOrder:$total, array('maxlength'=>5,'placeholder' => trans('contactinfo::template.common.order'),'class' => 'form-control','autocomplete'=>'off')) !!}
                                        <label class="form-label" class="site_name">{{ trans('contactinfo::template.common.displayorder') }} <span aria-required="true" class="required"> * </span></label>
                                        <span style="color: red;"><strong>{{ $errors->first('order') }}</strong></span>
                                    </div>
                                </div>
                            </div> --}}
                            <div class="col-md-12">
                                <div class="form-section">
                                    <button type="submit" name="saveandedit" formmethod="post" class="btn btn-primary bg-gradient waves-effect waves-light btn-label me-1" value="saveandedit">
                                        <div class="flex-shrink-0">
                                            <i class="ri-save-line label-icon align-middle fs-20 me-2"></i>
                                        </div>
                                        {!! trans('contactinfo::template.common.saveandedit') !!}
                                    </button>
                                    <button type="submit" name="saveandexit" formmethod="post" class="btn btn-secondary bg-gradient waves-effect waves-light btn-label me-1" value="saveandexit">
                                        <div class="flex-shrink-0">
                                            <i class="ri-save-3-line label-icon align-middle fs-20 me-2"></i>
                                        </div>
                                        {!! trans('contactinfo::template.common.saveandexit') !!}
                                    </button>
                                    <a class="btn btn-danger bg-gradient waves-effect waves-light btn-label me-1" href="{{ url('powerpanel/contact-info') }}">
                                        <div class="flex-shrink-0">
                                            <i class="ri-close-line label-icon align-middle fs-20 me-2"></i>
                                        </div>
                                        {{ trans('contactinfo::template.common.cancel') }}
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            {!! Form::close() !!}
        </div>
    </div>
</div>
@endsection
@section('scripts')
    <script type="text/javascript">
        window.site_url = '{!! url('/') !!}';
    </script>
    <script src="{{ $CDN_PATH . 'assets/libraries/masked-input/jquery.mask.min.js' }}"></script>
    <script src="{{ $CDN_PATH . 'resources/pages/scripts/packages/contactinfo/contacts_validations.js' }}"
        type="text/javascript"></script>
    <script src="{{ $CDN_PATH . 'resources/pages/scripts/numbervalidation.js' }}" type="text/javascript"></script>
    <script src="{{ $CDN_PATH . 'resources/pages/scripts/custom.js' }}" type="text/javascript"></script>
@endsection
