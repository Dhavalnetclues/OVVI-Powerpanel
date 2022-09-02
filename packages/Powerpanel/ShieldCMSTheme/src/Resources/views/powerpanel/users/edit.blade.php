@section('css')
<link href="{{ $CDN_PATH.'resources/global/plugins/select2/css/select2.min.css' }}" rel="stylesheet" type="text/css"/>
<link href="{{ $CDN_PATH.'resources/global/plugins/select2/css/select2-bootstrap.min.css' }}" rel="stylesheet" type="text/css"/>
@endsection
@extends('powerpanel.layouts.app')
@section('title')
{{Config::get('Constant.SITE_NAME')}} - PowerPanel
@endsection
@section('content')
@include('powerpanel.partials.breadcrumbs')
<div class="col-md-12 settings">
	<!-- @if (count($errors) > 0)
		<div class="alert alert-danger">
				<strong>Whoops!</strong> There were some problems with your input.<br><br>
				<ul>
						@foreach ($errors->all() as $error)
							<li>{{ $error }}</li>
						@endforeach
				</ul>
		</div>
	@endif -->
	{!! Form::model($user, ['method' => 'POST','id'=>'frmUsers','route' => ['powerpanel.users.edit', Crypt::encrypt($user->id)]]) !!}
	<input type="password" style="width: 0;height: 0; visibility: hidden;position:absolute;left:0;top:0;"/>
	<div class="row">
		@if(Session::has('message'))
		<div class="alert alert-success">
			<button class="close" data-close="alert"></button>
			{{ Session::get('message') }}
		</div>
		@endif
		<div class="portlet light bordered">
			<div class="portlet-body form_pattern">
				<div class="tabbable tabbable-tabdrop">
					<div class="tab-content settings">
						<div class="form-body">
							<div class="form-group {{ $errors->has('name') ? ' has-error' : '' }} form-md-line-input">
								{!! Form::text('name', null, array('maxlength'=>150,'placeholder'=>'Name','class' => 'form-control maxlength-handler','autocomplete'=>'off')) !!}
								<label class="form_title" for="name">{{  trans('shiledcmstheme::template.common.name') }} <span aria-required="true" class="required"> * </span></label>
								<span style="color: red;">
									{{ $errors->first('name') }}
								</span>
							</div>
							<div class="form-group {{ $errors->has('email') ? ' has-error' : '' }} form-md-line-input">
								{!! Form::text('email', null, array('maxlength'=>150,'placeholder'=>'Email','class' => 'form-control maxlength-handler','autocomplete'=>'off')) !!}
								<label class="form_title" for="email">{{  trans('shiledcmstheme::template.common.email') }}  <span aria-required="true" class="required"> * </span></label>
								<span style="color: red;">
									{{ $errors->first('email') }}
								</span>
							</div>
							<div class="form-group {{ $errors->has('password') ? ' has-error' : '' }} form-md-line-input">
								{!! Form::password('password',array('autocomplete' => 'off','maxlength'=>20,'placeholder'=>'Password','class' => 'form-control maxlength-handler')) !!}
								<label class="form_title" for="password">{{  trans('shiledcmstheme::template.common.password') }} </label>
								<span style="color: red;">
									{{ $errors->first('password') }}
								</span>
							</div>
							<div class="form-group form-md-line-input">
								{!! Form::password('confirm-password', array('autocomplete' => 'off','maxlength'=>20,'placeholder'=>'Confirm Password','class' => 'form-control maxlength-handler')) !!}
								<label class="form_title" for="confirm-password">{{  trans('shiledcmstheme::template.common.confirmpassword') }}</label>
							</div>
							<div class="form-group {{ $errors->has('roles') ? ' has-error' : '' }} form-md-line-input">
								{!! Form::select('roles[]',$roles,$userRole, array('class' => 'form-control bs-select select2','multiple')) !!}
								<label class="form_title" for="roles">{{  trans('shiledcmstheme::template.roles') }} <span aria-required="true" class="required"> * </span></label>
								<span style="color: red;">
									{{ $errors->first('roles') }}
								</span>
							</div>
							@include('powerpanel.partials.displayInfo',['display' => $user->chrPublish])
							<div class="form-actions">
								<div class="row">
									<div class="col-md-12">
										<button type="submit" name="saveandedit" class="btn btn-green-drake" value="saveandedit">{{  trans('shiledcmstheme::template.common.saveandedit') }}</button>
										<button type="submit" name="saveandexit" class="btn btn-green-drake" value="saveandexit">{{  trans('shiledcmstheme::template.common.saveandexit') }} </button>
										<a class="btn red btn-outline" href="{{url('powerpanel/users')}}">{{  trans('shiledcmstheme::template.common.cancel') }}</a>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
	{!! Form::close() !!}
</div>
<div class="clearfix"></div>
@endsection
@section('scripts')
<script type="text/javascript">var userAction='edit';</script>
<script src="{{ $CDN_PATH.'resources/pages/scripts/packages/users/user_validations.js' }}" type="text/javascript"></script>
<script src="{{ $CDN_PATH.'resources/global/plugins/bootstrap-maxlength/bootstrap-maxlength.min.js' }}" type="text/javascript"></script>
@endsection