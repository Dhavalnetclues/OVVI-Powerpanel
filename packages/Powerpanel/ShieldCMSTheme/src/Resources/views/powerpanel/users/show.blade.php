@extends('powerpanel.layouts.app')
@section('title')
{{Config::get('Constant.SITE_NAME')}} - PowerPanel
@endsection
@section('content')
@include('powerpanel.partials.breadcrumbs')
<div class="row">
	<div class="col-md-12">
		<div class="portlet light portlet-fit portlet-datatable" style="padding:15px 0">
			<div class="col-xs-12 col-sm-12 col-md-12">
				<div class="form-group">
					<strong>{{  trans('shiledcmstheme::template.common.name') }}:</strong>
					{{ $user->name }}
				</div>
			</div>
			<div class="col-xs-12 col-sm-12 col-md-12">
				<div class="form-group">
					<strong>{{  trans('shiledcmstheme::template.common.email') }}:</strong>
					{{ $user->email }}
				</div>
			</div>
			<div class="col-xs-12 col-sm-12 col-md-12">
				<div class="form-group">
					<strong>{{  trans('shiledcmstheme::template.common.roles') }}:</strong>
					@if(!empty($user->roles))
					@foreach($user->roles as $v)
					<label class="label label-success">{{ $v->display_name }}</label>
					@endforeach
					@endif
				</div>
			</div>
		</div>
	</div>
	@endsection