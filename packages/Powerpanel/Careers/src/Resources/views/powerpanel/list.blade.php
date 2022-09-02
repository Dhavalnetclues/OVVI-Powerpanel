 @if(isset($_REQUEST['company']))
<script>
    var companyid='{!!$_REQUEST["company"]!!}';
 
</script>
@else
<script>
    var companyid='';
 
</script>
@endif
@extends('powerpanel.layouts.app')
@section('title')
	{{Config::get('Constant.SITE_NAME')}} - PowerPanel
@stop
@section('css')
<!-- <link href="{{ $CDN_PATH.'resources/global/plugins/datatables/datatables.min.css' }}" rel="stylesheet" type="text/css" />
<link href="{{ $CDN_PATH.'resources/global/plugins/datatables/plugins/bootstrap/datatables.bootstrap.css' }}" rel="stylesheet" type="text/css" />
<link href="{{ $CDN_PATH.'resources/global/plugins/bootstrap-datepicker/css/bootstrap-datepicker3.min.css' }}" rel="stylesheet" type="text/css"/>
<link href="{{ $CDN_PATH.'resources/global/plugins/bootstrap-daterangepicker/daterangepicker.min.css' }}" rel="stylesheet" type="text/css" />
<link href="{{ $CDN_PATH.'resources/global/plugins/fancybox/source/helpers/jquery.fancybox-thumbs.css' }}" rel="stylesheet" type="text/css"/> -->
<link href="{{ $CDN_PATH.'resources/global/plugins/highslide/highslide.css' }}" rel="stylesheet" type="text/css" />
@endsection
@section('content')
{!! csrf_field() !!}

<div class="row">
	<div class="col-md-12">
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
        <div class="card">
			<div class="card-body border border-dashed border-end-0 border-start-0">
				<div class="row g-3">
					<div class="col-xxl-4 col-sm-4">
						<div class="search-box">
							<input type="search" class="form-control search" placeholder="Search by First Name" id="searchfilter" name="searchfilter">
							<i class="ri-search-line search-icon"></i>
						</div>
					</div>
					<div class="col-xxl-3 pull-left">
                        <div class="input-group input-daterange">
                            <span class="input-group-text" id="basic-addon1"><i class="ri-calendar-line"></i></span>
                            <input class="form-control" id="start_date" placeholder="Application Date" type="text" data-provider="flatpickr" data-date-format="{{ Config::get('Constant.DEFAULT_DATE_FORMAT') }}">
                            <button class="btn btn-outline-primary btn-rh-search" id="careerLeadRange" type="button"><i class="ri-search-line"></i></button>
                        </div>
                    </div>
					<div class="col-xxl-1 col-sm-2">
						<button type="button" class="btn btn-soft-secondary waves-effect waves-light btn-light btn-sm" title="Reset" id="refresh">
							<i class="ri-refresh-line"></i>
						</button>
					</div>
					<!--end col-->
				</div><!--end row-->
			</div><!-- end card body -->

			<div class="card-body">
				<div class="live-preview">
					<table class="table table-hover align-middle table-nowrap hide-mobile no-footer" id="datatable_ajax">
						<thead class="text-muted table-light">
							<tr role="row" class="heading">
								<th width="2%" align="center"><input type="checkbox" class="form-check-input group-checkable"></th>
								<th width="10%" align="left">First Name</th>
								<th width="10%" align="left">Last Name</th>
								<th width="10%" align="left">Email</th>
								<th width="10%" align="left">Post</th>
								<th width="10%" align="left">Phone No.</th>
								<th width="10%" align="left">{{ trans('Documents') }}</th>
								<th width="10%" align="left">Details</th>
								<th width="10%" align="left">Application Date</th>
							</tr>
						</thead>
						<tbody></tbody>
					</table>
					@can('careers-lead-delete')
					<a href="javascript:void(0);" class="btn rounded-pill btn-danger right_bottom_btn deleteMass">{{ trans('careers::template.common.delete') }}</a>
					@endcan
					<a href="#selectedRecords" class="btn rounded-pill btn-primary right_bottom_btn ExportRecord" data-bs-toggle="modal">{{ trans('careers::template.common.export') }}</a>
				</div>
			</div><!-- end card-body -->
        </div><!-- end card -->
	</div>
</div>

<div class="new_modal modal fade" id="noRecords" tabindex="-1" role="basic" aria-hidden="true">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				{{ trans('careers::template.common.alert') }}
				<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
			</div>
			<div class="modal-body text-center">{{ trans('careers::template.common.noExport') }} </div>
			<div class="modal-footer">
				<button type="button" class="btn btn-primary" data-bs-dismiss="modal">{{ trans('careers::template.common.ok') }}</button>
			</div>
		</div>
	</div>
</div>
<div class="new_modal modal fade" id="selectedRecords" tabindex="-1" role="basic" aria-hidden="true">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				{{ trans('careers::template.common.alert') }}
				<button type="button" class="close" data-bs-dismiss="modal" aria-hidden="true"></button>
			</div>
			<div class="modal-body text-center">
				<div class="row">
					<div class="col-12 mb-3">
						{{ trans('careers::template.common.recordsExport') }}
					</div>
					<div class="col-12">
						<div class="form-check form-check-inline">
							<input class="form-check-input" type="radio" value="selected_records" id="selected_records" name="export_type">
							<label for="form-check-label">{{ trans('careers::template.common.selectedRecords') }}</label>
						</div>
						<div class="form-check form-check-inline">
							<input class="form-check-input" type="radio" value="all_records" id="all_records" name="export_type" checked>
							<label for="form-check-label">{{ trans('careers::template.common.allRecords') }}</label>
						</div>
					</div>
				</div>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-primary" id="ExportRecord" data-bs-dismiss="modal">{{ trans('careers::template.common.ok') }} </button>
			</div>
		</div>
		<!-- /.modal-content -->
	</div>
	<!-- /.modal-dialog -->
</div>
<div class="new_modal modal fade" id="noSelectedRecords" tabindex="-1" role="basic" aria-hidden="true">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				{{ trans('careers::template.common.alert') }}
				<button type="button" class="close" data-bs-dismiss="modal" aria-hidden="true"></button>
			</div>
			<div class="modal-body text-center">{{ trans('careers::template.common.leastRecord') }} </div>
			<div class="modal-footer">
				<button type="button" class="btn btn-primary" data-bs-dismiss="modal">{{ trans('careers::template.common.ok') }} </button>
			</div>
		</div>
		<!-- /.modal-content -->
	</div>
	<!-- /.modal-dialog -->
</div>
<!-- /.modal -->
@include('powerpanel.partials.deletePopup')
@endsection
@section('scripts')
	<script type="text/javascript">
		window.site_url =  '{!! url("/") !!}';
		var DELETE_URL =  '{!! url("/powerpanel/careers-lead/DeleteRecord") !!}';
	</script>
        
       
        
	<!-- <script src="{{ $CDN_PATH.'resources/global/plugins/bootstrap-datepicker/js/bootstrap-datepicker.min.js' }}" type="text/javascript"></script> -->
	<script src="{{ $CDN_PATH.'resources/global/plugins/jquery-cookie-master/src/jquery.cookie.js' }}" type="text/javascript"></script>	
	<script src="{{ $CDN_PATH.'resources/global/plugins/datatables/datatables.min.js' }}" type="text/javascript"></script>
	<script src="{{ $CDN_PATH.'resources/global/scripts/datatable.js' }}" type="text/javascript"></script>
	<script src="{{ $CDN_PATH.'resources/global/plugins/datatables/plugins/bootstrap/datatables.bootstrap.js' }}" type="text/javascript"></script>
	<script src="{{ $CDN_PATH.'resources/pages/scripts/packages/careers/careerslead-datatables-ajax.js' }}" type="text/javascript"></script>
	<script src="{{ $CDN_PATH.'resources/pages/scripts/custom.js' }}" type="text/javascript"></script>
	<script src="{{ $CDN_PATH.'resources/global/plugins/moment.min.js' }}"></script>
	<script src="{{ $CDN_PATH.'resources/global/plugins/highslide/highslide-with-html.js' }}" type="text/javascript"></script>
<!-- <script>
	$(document).ready(function () {
			var today = moment.tz("{{Config::get('Constant.DEFAULT_TIME_ZONE')}}").format(DEFAULT_DT_FORMAT);
			$('#start_date').datepicker({
					autoclose: true,
					//startDate: today,
					minuteStep: 5,
					format: DEFAULT_DT_FMT_FOR_DATEPICKER
			}).on("changeDate", function (e) {
					$("#start_date").closest('.has-error').removeClass('has-error');
					$("#app_post_date-error").remove();
					$('#end_date').val('');
					var endingdate = $(this).val();
					var date = new Date(endingdate);
					var enddate = new Date(date.getFullYear(), date.getMonth(), date.getDate());
					$('#end_date').datepicker('remove');
					$('#end_date').datepicker({
							autoclose: true,
							startDate: enddate,
							minuteStep: 5,
							format: DEFAULT_DT_FMT_FOR_DATEPICKER
					});
			});
			var endingdate = $('#start_date').val();
			var date = new Date(endingdate);
			var enddate = new Date(date.getFullYear(), date.getMonth(), date.getDate());
			$('#end_date').datepicker({
					autoclose: true,
					startDate: enddate,
					minuteStep: 5,
					format: DEFAULT_DT_FMT_FOR_DATEPICKER
			});
	});
</script> -->
<script src="{{ $CDN_PATH.'resources/global/plugins/moment.min.js' }}"></script>
    <script src="{{ $CDN_PATH.'resources/global/plugins/moments-timezone.js' }}"></script>
@endsection