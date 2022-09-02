@extends('powerpanel.layouts.app')
@section('title')
    {{ Config::get('Constant.SITE_NAME') }} - PowerPanel
@stop
@section('css')
    <link href="{{ $CDN_PATH . 'resources/global/plugins/datatables/datatables.min.css' }}" rel="stylesheet" type="text/css" />
    <link href="{{ $CDN_PATH . 'resources/global/plugins/datatables/plugins/bootstrap/datatables.bootstrap.css' }}" rel="stylesheet" type="text/css" />
    <!-- <link href="{{ $CDN_PATH . 'resources/global/plugins/bootstrap-datepicker/css/bootstrap-datepicker3.min.css' }}" rel="stylesheet" type="text/css" /> -->
    <link href="{{ $CDN_PATH . 'resources/global/plugins/highslide/highslide.css' }}" rel="stylesheet" type="text/css" />
@endsection
@section('content')
@include('powerpanel.partials.breadcrumbs')
    {!! csrf_field() !!}

    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header border border-dashed border-end-0 border-start-0">
                    <div class="row g-3">
                        <div class="col-xxl-4 col-sm-6">
                            <div class="search-box">
                                <input type="search" class="form-control search" placeholder="Search by Name" id="searchfilter">
                                <i class="ri-search-line search-icon"></i>
                            </div>
                        </div>
                        <div class="col-lg-6 col-md-3 col-xs-3">
                            <div class="input-group new_date_picker date-picker input-daterange">
                                <span class="input-group-text"><i class="ri-calendar-line"></i></span>
                                <input type="text" class="form-control" id="start_date" name="start_date" placeholder="Start Date" readonly data-provider="flatpickr" data-date-format="{{Config::get('Constant.DEFAULT_DATE_FORMAT')}}">
                                <span class="input-group-text"><i class="ri-calendar-line"></i></span>
                                <input type="text" class="form-control" id="end_date" name="end_date" placeholder="To Date" readonly data-provider="flatpickr" data-date-format="{{Config::get('Constant.DEFAULT_DATE_FORMAT')}}">
                                <button class="btn btn-outline-primary btn-rh-search" id="payonlinerange" type="button"><i class="ri-search-line"></i></button>
                            </div>
                        </div>
                        <!--end col-->
                        <div class="col-xxl-1 col-sm-2">
                            <button type="button" class="btn btn-primary" title="Reset" id="refresh">
                                <i class="ri-refresh-line"></i>
                            </button>
                        </div>
                        <!--end col-->
                    </div><!--end row-->
                </div>

                @if($iTotalRecords > 0)
                    <div class="card-body">
                        <div class="live-preview">
                            <div class="table-responsive">
                                <table class="table table-striped table-bordered table-hover table-checkable hide-mobile" id="datatable_ajax">
                                    <thead class="text-muted table-light">
                                        <tr role="row" class="heading">
                                            <th width="2%" align="center"><input type="checkbox" class="form-check-input group-checkable"></th>
                                            <th width="20%" align="center">{{ trans('payonline::template.payonlineModule.name') }}</th>
                                            <th width="20%" align="center">{{ trans('payonline::template.payonlineModule.email') }}</th>
                                            <th width="20%" align="center">{{ trans('payonline::template.payonlineModule.transactionId') }}</th>
                                            <th width="10%" align="center">Transaction Status</th>
                                            <th width="5%" align="center">Transaction Details</th>
                                            <th width="20%" align="center">{{ trans('payonline::template.payonlineModule.payment_date') }}</th>
                                        </tr>
                                    </thead>
                                    <tbody></tbody>
                                </table>
                                @can('complaint-delete')
                                <a href="javascript:void(0);" class="btn-sm rounded-pill btn btn-danger right_bottom_btn deleteMass">{{ trans('payonline::template.common.delete') }}</a>
                            @endcan
                            <a href="#selectedRecords" class="btn-sm rounded-pill btn btn-primary right_bottom_btn ExportRecord"
                                data-bs-toggle="modal">{{ trans('payonline::template.payonlineModule.export') }}</a>
                            </div>
                        </div>
                    </div><!-- end card-body -->
                @else
                    @include('powerpanel.partials.addrecordsection',['marketlink' =>
                    'https://www.netclues.com/social-media-marketing', 'type'=>'Pay Online'])
                @endif
            </div>
        </div>
    </div>



    <div class="new_modal modal fade" id="noRecords" tabindex="-1" role="basic" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-vertical">
                <div class="modal-content">
                    <div class="modal-header">
                        {{ trans('payonline::template.common.alert') }}
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"> </button>
                    </div>
                    <div class="modal-body text-center">{{ trans('payonline::template.payonlineModule.noExport') }}</div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-primary" data-bs-dismiss="modal">{{ trans('payonline::template.common.ok') }}</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="new_modal modal fade" id="selectedRecords" tabindex="-1" role="basic" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-vertical">
                <div class="modal-content">
                    <div class="modal-header">
                        {{ trans('payonline::template.common.alert') }}
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"> </button>
                    </div>
                    <div class="modal-body text-center">
                        <div class="row">
                            <div class="col-12 mb-3">
                                {{ trans('payonline::template.payonlineModule.recordsExport') }}
                            </div>
                            <div class="col-12">
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="radio" value="selected_records" id="selected_records" name="export_type">
                                    <label for="form-check-label">{{ trans('payonline::template.payonlineModule.selectedRecords') }}</label>
                                </div>
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="radio" value="all_records" id="all_records" name="export_type" checked>
                                    <label for="form-check-label">{{ trans('payonline::template.payonlineModule.allRecords') }}</label>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-primary" id="ExportRecord" data-bs-dismiss="modal">{{ trans('payonline::template.common.ok') }} </button>
                    </div>
                </div>
                <!-- /.modal-content -->
            </div>
        </div>
        <!-- /.modal-dialog -->
    </div>
    <div class="new_modal modal fade" id="noSelectedRecords" tabindex="-1" role="basic" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-vertical">
                <div class="modal-content">
                    <div class="modal-header">
                        {{ trans('payonline::template.common.alert') }}
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"> </button>
                    </div>
                    <div class="modal-body text-center">{{ trans('payonline::template.payonlineModule.leastRecord') }}</div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-primary" data-bs-dismiss="modal">{{ trans('payonline::template.common.ok') }} </button>
                    </div>
                </div>
                <!-- /.modal-content -->
            </div>
        </div>
        <!-- /.modal-dialog -->
    </div>
    <!-- /.modal -->
    @include('powerpanel.partials.deletePopup')
@endsection
@section('scripts')
    <script type="text/javascript">
        window.site_url = '{!! url('/') !!}';
        var DELETE_URL = '{!! url('/powerpanel/payonline/DeleteRecord') !!}';
    </script>

    <!-- <script src="{{ $CDN_PATH . 'resources/global/plugins/bootstrap-datepicker/js/bootstrap-datepicker.min.js' }}" -->
        type="text/javascript"></script>
    <script src="{{ $CDN_PATH . 'resources/global/plugins/jquery-cookie-master/src/jquery.cookie.js' }}"
        type="text/javascript"></script>
    <script src="{{ $CDN_PATH . 'resources/global/plugins/datatables/datatables.min.js' }}" type="text/javascript">
    </script>
    <script src="{{ $CDN_PATH . 'resources/global/scripts/datatable.js' }}" type="text/javascript"></script>
    <script src="{{ $CDN_PATH . 'resources/global/plugins/datatables/plugins/bootstrap/datatables.bootstrap.js' }}"
        type="text/javascript"></script>
    <script src="{{ $CDN_PATH . 'resources/pages/scripts/packages/payonline/payonline-datatables-ajax.js' }}"
        type="text/javascript"></script>
    <script src="{{ $CDN_PATH . 'resources/pages/scripts/custom.js' }}" type="text/javascript"></script>
    <script src="{{ $CDN_PATH . 'resources/global/plugins/moment.min.js' }}"></script>
    <script src="{{ $CDN_PATH . 'resources/global/plugins/highslide/highslide-with-html.js' }}" type="text/javascript">
    </script>
    <!-- <script>
        $(document).ready(function() {
            var today = moment.tz("{{ Config::get('Constant.DEFAULT_TIME_ZONE') }}").format(DEFAULT_DT_FORMAT);
            $('#start_date').datepicker({
                autoclose: true,
                //startDate: today,
                minuteStep: 5,
                format: DEFAULT_DT_FMT_FOR_DATEPICKER
            }).on("changeDate", function(e) {
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
    <script src="{{ $CDN_PATH . 'resources/global/plugins/moment.min.js' }}"></script>
    <script src="{{ $CDN_PATH . 'resources/global/plugins/moments-timezone.js' }}"></script>
@endsection
