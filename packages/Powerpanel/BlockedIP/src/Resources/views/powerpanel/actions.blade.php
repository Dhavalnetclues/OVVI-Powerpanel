@extends('powerpanel.layouts.app')
@section('title')
{{Config::get('Constant.SITE_NAME')}} - PowerPanel
@stop
@section('content')
@php $settings = json_decode(Config::get("Constant.MODULE.SETTINGS")); @endphp

<div class="col-md-12 settings">
    <div class="row">
        @if(Session::has('message'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ Session::get('message') }}
        </div>
        @endif
        @if(Session::has('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            {{ Session::get('error') }}
        </div>
        @endif

        <div class="live-preview">
            {!! Form::open(['method' => 'post','id'=>'frmblockips']) !!}
                <div class="card">
                    <div class="card-body p-30">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group @if($errors->first('tag_line')) has-error @endif form-md-line-input cm-floating">
                                    <label class="form_title" for="site_name">IP Address <span aria-required="true" class="required"> * </span></label>
                                    {!! Form::text('ip_address', isset($department->varTitle) ? $department->varTitle:old('ip_address'), array('maxlength'=>'20','placeholder' => 'IP Address','class' => 'form-control maxlength-handler','autocomplete'=>'off','onkeypress'=>'javascript: return KeycheckOnlyAmount(event);')) !!}
                                    <span class="help-block">{{ $errors->first('ip_address') }}</span>
                                </div>
                            </div>
                            <div class="col-md-12">
                                @if($userIsAdmin)
                                <button type="submit" name="saveandexit" class="btn btn-primary bg-gradient waves-effect waves-light btn-label me-1" value="saveandexit">
                                    <div class="d-flex">
                                        <div class="flex-shrink-0">
                                            <i class="ri-save-3-line label-icon align-middle fs-20 me-2"></i>
                                        </div>
                                        <div class="flex-grow-1">
                                            {!! trans('template.common.saveandexit') !!}
                                        </div>
                                    </div>
                                </button>
                                @endif
                                <a class="btn btn-danger bg-gradient waves-effect waves-light btn-label me-1" href="{{ url('powerpanel/blocked-ips') }}">
                                    <div class="d-flex">
                                        <div class="flex-shrink-0">
                                            <i class="ri-close-line label-icon align-middle fs-20 me-2"></i>
                                        </div>
                                        <div class="flex-grow-1">
                                            {{ trans('template.common.cancel') }}
                                        </div>
                                    </div>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            {!! Form::close() !!}
        </div>
    </div>
</div>
<div class="clearfix"></div>
@endsection
@section('scripts')
<script type="text/javascript">
    window.site_url = '{!! url("/") !!}';
    var user_action = "{{ isset($department)?'edit':'add' }}";
    var moduleAlias = 'department';
</script>
<script src="{{ $CDN_PATH.'resources/pages/scripts/packages/blockip/blocked_ips_validations.js' }}" type="text/javascript"></script>
<script src="{{ $CDN_PATH.'resources/pages/scripts/numbervalidation.js' }}" type="text/javascript"></script>
<script src="{{ $CDN_PATH.'resources/pages/scripts/custom.js' }}" type="text/javascript"></script>
<script src="{{ $CDN_PATH.'resources/global/plugins/bootstrap-maxlength/bootstrap-maxlength.min.js' }}" type="text/javascript"></script>
@endsection