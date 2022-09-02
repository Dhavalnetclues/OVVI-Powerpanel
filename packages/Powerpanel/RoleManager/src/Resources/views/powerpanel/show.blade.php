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
					<strong>{{ trans('rolemanager::template.common.name') }}:</strong>
					{{ $role->display_name }}
				</div>
			</div>
			<div class="col-xs-12 col-sm-12 col-md-12">
				<div class="form-group">
					<strong>{{ trans('rolemanager::template.common.description') }}:</strong>
					{{ $role->description }}
				</div>
			</div>
			<div class="col-xs-12 col-sm-12 col-md-12">
				<div class="form-group">
					<strong>{{ trans('rolemanager::template.common.permission') }}:</strong>
				</div>
				<div class="col-xs-12 col-sm-12 col-md-12">
					<div class="row">
						@if(!empty($rolePermissions))
						@foreach($rolePermissions as $v)
						<div class="col-md-3">
							@php
							$permission = $v['permission_role']['name'];
							$permission = str_replace('settings-', '', $permission);
							$permission = str_replace('-', ' ', $permission);
							@endphp
							<label class="label label-success" style="display:inline-block;">{{ $permission }}</label>
						</div>
						@endforeach
						@endif
					</div>
				</div>
			</div>
		</div>
	</div>
	@endsection