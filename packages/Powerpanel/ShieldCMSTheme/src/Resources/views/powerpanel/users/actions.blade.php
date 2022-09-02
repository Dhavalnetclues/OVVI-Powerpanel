@section('css')
@endsection
@extends('powerpanel.layouts.app')
@section('title')
{{Config::get('Constant.SITE_NAME')}} - PowerPanel
@endsection
@section('content')
@php $settings = json_decode(Config::get("Constant.MODULE.SETTINGS")); @endphp

<div class="row">
	<div class="col-md-12 settings">
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
			@if(isset($user))
			{!! Form::model($user, ['method' => 'POST','id'=>'frmUsers','route' => ['powerpanel.users.edit', $user->id]]) !!}
			@else
			{!! Form::model(null,['method' => 'POST','id'=>'frmUsers','route' => ['powerpanel.users.add']]) !!}
			@endif
				<div class="card">
					<div class="card-body p-30">
						<div class="row">
							<div class="col-md-12">
								<input type="password" style="width: 0;height: 0; visibility: hidden;position:absolute;left:0;top:0;"/>
							</div>
							<div class="col-md-12">
								<div class="{{ $errors->has('name') ? ' has-error' : '' }} form-md-line-input cm-floating">
									<label class="form-label focus-none" for="name">{{  trans('shiledcmstheme::template.common.name') }} <span aria-required="true" class="required"> * </span></label>	
									{!! Form::text('name', null, array('maxlength'=>150,'class' => 'form-control input-sm maxlength-handler','autocomplete'=>'off')) !!}
									<span style="color: red;">{{ $errors->first('name') }}</span>
								</div>
							</div>
							<div class="col-md-12">
								<div class="{{ $errors->has('email') ? ' has-error' : '' }} form-md-line-input cm-floating">
									<label class="form-label focus-none" for="email">{{  trans('shiledcmstheme::template.common.email') }}  <span aria-required="true" class="required"> * </span></label>
									{!! Form::text('email', null, array('maxlength'=>150,'class' => 'form-control input-sm maxlength-handler','autocomplete'=>'off')) !!}
									<span style="color: red;">{{ $errors->first('email') }}</span>
								</div>
							</div>
							<div class="col-md-12">
								<div class="{{ $errors->has('password') ? ' has-error' : '' }} form-md-line-input cm-floating">
									<label class="form-label focus-none" for="password">{{  trans('shiledcmstheme::template.common.password') }} </label>	
									{!! Form::password('password',array('autocomplete' => 'off','maxlength'=>20,'class' => 'form-control input-sm maxlength-handler','id'=>'password')) !!}
									<span style="color: red;">{{ $errors->first('password') }}</span>
									<div class="pswd_info" id="password_info">
										<h4>Password must meet the following requirements:</h4>
										<ul>
											<li id="letter" class="letterinfo invalid">At least <strong>one lowercase letter</strong></li>
											<li id="capital" class="capitalletterinfo invalid">At least <strong>one uppercase letter</strong></li>
											<li id="number" class="numberinfo invalid">At least <strong>one number</strong></li>
											<li id="length" class="lengthInfo invalid">Password should be <strong>6 to 20 characters</strong></li>
											<li id="special" class="specialinfo invalid">At least <strong>one special character</strong></li>
										</ul>
									</div>
								</div>
							</div>
							<div class="col-md-12">
								<div class="form-md-line-input cm-floating">
									<label class="form-label focus-none" for="confirm-password">{{  trans('shiledcmstheme::template.common.confirmpassword') }}</label>
									{!! Form::password('confirm-password', array('autocomplete' => 'off','maxlength'=>20,'class' => 'form-control input-sm maxlength-handler','id'=>'confirmpassword')) !!}
								</div>
							</div>
							<div class="col-md-12">
								@if(isset($user) && $user->id==1)
								{!! Form::hidden('roles[]','1') !!}
								@else
								<div class="{{ $errors->has('roles') ? ' has-error' : '' }} form-md-line-input cm-floating">
									<label class="form-label focus-none" for="roles">Assign Role <span aria-required="true" class="required"> * </span></label>	
									{!! Form::select('roles[]',$roles,isset($userRole)?$userRole:old('roles'), array('class' => 'form-select', 'data-choices')) !!}
									<span style="color: red;">{{ $errors->first('roles') }}</span>
								</div>
								@endif
							</div>
							<div class="col-md-12">
								<div class="mb-3">
									@if(isset($user) && $user->id == '1')
										{!! Form::hidden('chrMenuDisplay', 'Y') !!}
									@else
										@include('powerpanel.partials.displayInfo',['display' => isset($user->chrPublish)?$user->chrPublish:null])
									@endif
								</div>
							</div>
						</div>

						<div class="form-actions btn-bottom">
							<div class="row">
								<div class="col-md-12">
									<button type="submit" name="saveandedit" formmethod="post" class="btn btn-primary bg-gradient waves-effect waves-light btn-label me-1" value="saveandedit">
										<div class="flex-shrink-0">
											<i class="ri-save-line label-icon align-middle fs-20 me-2"></i>
										</div>
										{!! trans('shiledcmstheme::template.common.saveandedit') !!}
									</button>
									<button type="submit" name="saveandexit" formmethod="post" class="btn btn-secondary bg-gradient waves-effect waves-light btn-label me-1" value="saveandexit">
										<div class="flex-shrink-0">
											<i class="ri-save-line label-icon align-middle fs-20 me-2"></i>
										</div>
										{!! trans('shiledcmstheme::template.common.saveandexit') !!}
									</button>
									<a class="btn btn-danger bg-gradient waves-effect waves-light btn-label" href="{{ url('powerpanel/users') }}">
										<div class="flex-shrink-0">
											<i class="ri-close-line label-icon align-middle fs-20 me-2"></i>
										</div>
										{{ trans('shiledcmstheme::template.common.cancel') }}
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
<div class="clearfix"></div>
@endsection
@section('scripts')
<script type="text/javascript">
		var userAction = "{{ (isset($user)) ? 'edit':'' }}";
</script>
<script src="{{ $CDN_PATH.'resources/pages/scripts/packages/users/user_validations.js' }}" type="text/javascript"></script>
<script src="{{ $CDN_PATH.'resources/global/plugins/bootstrap-maxlength/bootstrap-maxlength.min.js' }}" type="text/javascript"></script>
<script src="{{ $CDN_PATH.'resources/pages/scripts/password_rules.js' }}" type="text/javascript"></script>
@endsection