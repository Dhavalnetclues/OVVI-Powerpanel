<!doctype html>
<html>
	<head>
		<title>{{ Config::get('Constant.SITE_NAME') }} Log Details</title>
	</head>
	<body>
		@if(isset($logsLeads) && !empty($logsLeads))
		<div class="row">
			<div class="col-12">
				<table class="search-result allData" id="" border="1">
					<thead>
						<tr>
							<th style="font-weight: bold;text-align:center" colspan="6">{{ Config::get('Constant.SITE_NAME') }} Log Details</th>
						</tr>
						<tr>
							<th style="font-weight: bold;">{{  trans('shiledcmstheme::template.common.name') }}</th>
							<th style="font-weight: bold;">User Role</th>
							<th style="font-weight: bold;">{{  trans('shiledcmstheme::template.common.email') }}</th>
							<th style="font-weight: bold;">Action</th>
							<th style="font-weight: bold;">ModuleName</th>
							<th style="font-weight: bold;">Record Title</th>
							<th style="font-weight: bold;">IP Address</th>
							<th style="font-weight: bold;">RECEIVED DATE/TIME</th>
						</tr>
					</thead>
					<tbody>
						@foreach($logsLeads as $row)
							@php
							$roledata = \App\Role::GetRoleTitle($row->fkIntUserId);
							$userName = $row->user->name;
							$useremail = \App\Helpers\Mylibrary::getDecryptedString($row->user->email);
							$action = $row->varAction;
							$moduleName = $row->module->varTitle;
							@endphp
						<tr>
							<td>{{ $userName }}</td>
							<td>{{ $roledata }}</td>
							<td>{{ $useremail }}</td>
							<td>{{ $action }}</td>
							<td>{{ $moduleName }}</td>
							<td>{{ ($row->varTitle != null) ? $row->varTitle : '-' }}</td>
							<td>{{ $row->varIpAddress }}</td>
							<td>{{ date('' . Config::get('Constant.DEFAULT_DATE_FORMAT') . ' ' . Config::get('Constant.DEFAULT_TIME_FORMAT') . '', strtotime($row->created_at)) }}</td>
						</tr>
						@endforeach
					</tbody>
				</table>
			</div>
		</div>
		@endif
	</body>
</html>