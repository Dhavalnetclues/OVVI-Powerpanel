@extends('powerpanel.layouts.app')
@section('title')
{{Config::get('Constant.SITE_NAME')}} - PowerPanel
@stop
@section('content')
@include('powerpanel.partials.breadcrumbs')

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
            <div class="card-header border border-dashed border-end-0 border-start-0">
                <div class="row g-3">
                    <div class="col-xxl-8 col-sm-4" id="hidefilter">
                        <select class="form-select" id="pageHitsChartFilter" onchange="" placeholder="filter" data-choices data-choices-search-false style="width:100px">
                            @php 
                            $currentYear = date('Y');
                            $pastTenYears = $currentYear - 9;
                            $futureTenYears = $currentYear; 
                            $yearOptions = range($pastTenYears,$futureTenYears);
                            arsort($yearOptions);
                            $i = 0;
                            @endphp
                            @foreach($yearOptions as $year)
                            <option value="{{ $i }}" data-value="{{ $year }}">{{ $year }}</option>
                            @php
                            $i++;
                            @endphp
                            @endforeach
                        </select>
                    </div><!--end col-->

                    <div class="col-xxl-4 col-sm-2 text-end">
                        <a href="javaScript:void(0);" id="Send_Report_Email" title="Send Report" class="btn btn-primary add_category"><span>Send Report</span> <i class="la la-envelope-o"></i></a>
                    </div><!--end col-->
                </div><!--end row-->
            </div><!-- end card header -->

            <div class="card-body">
                <div class="live-preview">
                    <div id="doc-chart" data-colors='["--vz-primary", "--vz-danger", "--vz-warning", "--vz-success"]' class="apex-charts" dir="ltr"></div>
                </div>
            </div><!-- end card-body -->
        </div>
    </div>
</div>

<div class="modal fade bs-example-modal-md" tabindex="-1" role="dialog" id="ReportModel" aria-labelledby="ReportModel" aria-hidden="true">
    <div class="modal-dialog modal-md">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title">Send Report Email</h5>
				<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
			</div>
            {!! Form::open(['method' => 'post','class'=>'HitsReportForm','id'=>'HitsReportForm']) !!}
            {!! Form::hidden('chart_div','',array('id' => 'chart_div')) !!}
            {!! Form::hidden('year','',array('id' => 'year')) !!}
			<div class="modal-body">
                <div class="mb-3">
                    <label for="to">Name: <span aria-required="true" class="required"> * </span></label>
                    {!! Form::text('Report_Name',  old('Report_Name') , array('id' => 'Report_Name', 'class' => 'form-control', 'placeholder'=>'Name')) !!}
                </div>
                <div class="mb-3">
                    <label for="to">Email: <span aria-required="true" class="required"> * </span></label>
                    {!! Form::text('Report_email',  old('Report_email') , array('id' => 'Report_email', 'class' => 'form-control', 'placeholder'=>'Email')) !!}
                </div>
                <div class="success"></div>
                <label class="error"></label>
            </div>
			<div class="modal-footer">
                <button type="submit" class="btn btn-primary" id="report_for_submit" value="saveandexit">Submit</button>
			</div>
            {!! Form::close() !!}
		</div>
	</div>
</div><!-- /.modal -->

@endsection
@section('scripts')
<script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
<script src="{{ $CDN_PATH.'resources/pages/scripts/packages/documentreport/hits-report-datatables-ajax.js' }}" type="text/javascript"></script>
<script src="{{ $CDN_PATH.'resources/pages/scripts/packages/documentreport/document-chart.js' }}" type="text/javascript"></script>
<script type="text/javascript">
    docChartData({!! $docChartData !!});
    var Email_Send_Report_URL = '{!! url("/powerpanel/document-report/sendreport") !!}';
</script>
@endsection