@extends('powerpanel.layouts.app')
    @section('title')
        {{Config::get('Constant.SITE_NAME')}} - PowerPanel
    @endsection
    @section('css')
        <link href="{{ $CDN_PATH.'resources/global/plugins/highslide/highslide.css' }}" rel="stylesheet" type="text/css"/>
    @endsection
@section('content')

<!-- BEGIN PAGE BASE CONTENT -->
<div class="row">
    <div class="col-xl-12">
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
        	@if($iTotalRecords > 0)

                <div class="card-header cmpage-topheader border border-dashed border-end-0 border-start-0 border-top-0">
                    <div class="d-xl-flex flex-wrap align-items-center">

                        <div class="cm-filter flex-grow-1 order-sm-1 d-flex align-items-center">
                            @if (File::exists(base_path() . '/resources/views/powerpanel/partials/tabpanel.blade.php') != null)
                                {{-- @include('powerpanel.partials.tabpanel',['tabarray'=>[]]) --}}
                            @endif
                            <div class="btn-group d-inline-block filter-dropdown">
                                <button type="button" class="btn fs-14 fw-medium p-0 border-0 filter-btn" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><i class="ri-filter-line fs-21"></i></button>
                                <div class="dropdown-menu">
                                    <div class="p-3 dropdown-body">

                                        <div class="mb-3">
                                            <div class="input-group input-daterange" id="contactleadrange">
                                                <span class="input-group-text" id="basic-addon1"><i class="ri-calendar-2-line fs-14"></i></span>
                                                <input class="form-control" id="start_date" name="start_date" placeholder="{{ trans('whitelabellead::template.common.fromdate') }}" type="text" data-provider="flatpickr" data-date-format="{{ Config::get('Constant.DEFAULT_DATE_FORMAT') }}">
                                            </div>
                                        </div>

                                        <div class="mb-3">
                                            <div class="input-group input-daterange" id="contactleadrange">
                                                <span class="input-group-text" id="basic-addon1"><i class="ri-calendar-2-line fs-14"></i></span>
                                                <input class="form-control" id="end_date" name="end_date" placeholder="{{ trans('whitelabellead::template.common.todate') }}" type="text" data-provider="flatpickr" data-date-format="{{ Config::get('Constant.DEFAULT_DATE_FORMAT') }}">
                                            </div>
                                        </div>

                                        <div class="reset-btn">
                                            <button type="button" class="btn btn-light bg-gradient waves-effect waves-light btn-light btn-label" data-bs-toggle="tooltip" data-bs-placement="bottom" title="Reset" id="refresh">
                                                <div class="d-flex">
                                                    <div class="flex-shrink-0">
                                                        <i class="ri-refresh-line label-icon align-middle fs-18 me-2"></i>
                                                    </div>
                                                    <div class="flex-grow-1">
                                                        Reset
                                                    </div>
                                                </div>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="filter-search d-inline-block">
                                <input type="search" class="form-control search" placeholder="Search by Name" id="searchfilter">
                                <span class="iconsearch cursor-pointer"><i class="ri-search-2-line fs-21"></i></span>
                            </div>
                        </div>

                    </div>
                </div><!-- end card header -->

                <div class="card-body">
                    <div class="live-preview">
                        <div class="table-responsive">
                            @php
                                $tablearray = [
                                    'DataTableTab'=>[
                                        'ColumnSetting'=>[
                                            ['Identity_Name'=>'name','TabIndex'=>'1','Name'=>'Name'],
                                            ['Identity_Name'=>'email','TabIndex'=>'2','Name'=>'Email'],
                                            ['Identity_Name'=>'phone','TabIndex'=>'3','Name'=>'Phone'],
                                            ['Identity_Name'=>'business','TabIndex'=>'4','Name'=>'business'],
                                            ['Identity_Name'=>'message','TabIndex'=>'5','Name'=>'Message'],
                                            ['Identity_Name'=>'ip','TabIndex'=>'6','Name'=>'IP'],
                                            ['Identity_Name'=>'date','TabIndex'=>'7','Name'=>'Date']
                                        ],
                                        'DataTableHead'=>[
                                            ['Title'=>'Name','Align'=>'left'],
                                            ['Title'=>'Email','Align'=>'left'],
                                            ['Title'=>'Phone','Align'=>'left'],
                                            ['Title'=>'business','Align'=>'left'],
                                            ['Title'=>'Message','Align'=>'left'],
                                            ['Title'=>'IP','Align'=>'left'],
                                            ['Title'=>'Received Date','Align'=>'center']
                                        ]
                                    ]
                                ];
                            @endphp
                            @include('powerpanel.partials.datatable-view',['ModuleName'=>'WhiteLabelLead','Permission_Delete'=>'whitelabel-delete','tablearray'=>$tablearray,'userIsAdmin'=>$userIsAdmin,'Module_ID'=>Config::get('Constant.MODULE.ID')])

                            {{-- @can('whitelabel-delete')
                                <a href="javascript:void(0);" class="btn btn-danger bg-gradient waves-effect waves-light btn-label deleteMass">
                                    <div class="d-flex">
                                        <div class="flex-shrink-0">
                                            <i class="ri-delete-bin-line label-icon align-middle fs-20 me-2"></i>
                                        </div>
                                        <div class="flex-grow-1">
                                            {{ trans('whitelabellead::template.common.delete') }}
                                        </div>
                                    </div>
                                </a>
                            @endcan --}}
                            {{-- <a href="#selectedRecords" class="btn btn-primary bg-gradient waves-effect waves-light btn-label ExportRecord ms-1" data-bs-toggle="modal">
                                <div class="d-flex">
                                    <div class="flex-shrink-0">
                                        <i class="ri-share-box-line label-icon align-middle fs-20 me-2"></i>
                                    </div>
                                    <div class="flex-grow-1">
                                        {{ trans('whitelabellead::template.WhiteLabelLeadModule.export') }}
                                    </div>
                                </div>
                            </a> --}}
                        </div>
                    </div>
                </div><!-- end card-body -->

                <!-- Modal -->
                @if (File::exists(base_path() . '/resources/views/powerpanel/partials/quickeditpopup.blade.php') != null)
                @include('powerpanel.partials.quickeditpopup',['TableName'=>'WhiteLabelLead'])
                @endif
                @else
                @if (File::exists(base_path() . '/resources/views/powerpanel/partials/addrecordsection.blade.php') != null)
                @include('powerpanel.partials.addrecordsection',['type'=>Config::get('Constant.MODULE.TITLE'), 'marketlink' => 'https://www.netclues.ky/it-services/digital-marketing/social-media-marketing-cayman-islands'])
                @endif
            @endif

        </div><!-- end card -->
    </div>
    <!-- end col -->
</div>


@if (File::exists(base_path() . '/resources/views/powerpanel/partials/deletePopup.blade.php') != null)
@include('powerpanel.partials.deletePopup')
@endif


<div class="new_modal modal fade" id="noRecords" tabindex="-1" role="basic" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">{{ trans('whitelabellead::template.common.alert') }}</h5>
                <button type="button" class="btn-close fs-10" data-bs-dismiss="modal" aria-label="Close"> </button>
            </div>
            <div class="modal-body text-center">
                <h5 class="mb-2">{{ trans('whitelabellead::template.WhiteLabelLeadModule.noExport') }}</h5>
                <div class="pt-2">
                    <button type="button" class="btn btn-primary bg-gradient waves-effect waves-light btn-label" data-bs-dismiss="modal">
                        <div class="d-flex">
                            <div class="flex-shrink-0">
                                <i class="ri-check-line label-icon align-middle fs-20 me-2"></i>
                            </div>
                            <div class="flex-grow-1">
                                {{ trans('whitelabellead::template.common.ok') }}
                            </div>
                        </div>
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="new_modal modal fade" id="selectedRecords" tabindex="-1" role="basic" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">{{ trans('whitelabellead::template.common.alert') }}</h5>
                <button type="button" class="btn-close fs-10" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body text-center">
				<div class="row">
					<div class="col-sm-12">
                        <h5 class="mb-2">{{ trans('whitelabellead::template.WhiteLabelLeadModule.recordsExport') }}</h5>
						<div class="form-check form-check-inline">
							<input class="form-check-input" type="radio" value="selected_records" id="selected_records" name="export_type">
							<label for="selected_records">{{ trans('whitelabellead::template.WhiteLabelLeadModule.selectedRecords') }}</label>
						</div>
						<div class="form-check form-check-inline">
							<input class="form-check-input" type="radio" value="all_records" id="all_records" name="export_type" checked>
							<label for="all_records">{{ trans('whitelabellead::template.WhiteLabelLeadModule.allRecords') }}</label>
						</div>
					</div>
                    <div class="col-sm-12 mt-3">
                        <button type="button" class="btn btn-primary bg-gradient waves-effect waves-light btn-label" id="ExportRecord" data-bs-dismiss="modal">
                            <div class="d-flex">
                                <div class="flex-shrink-0">
                                    <i class="ri-check-line label-icon align-middle fs-20 me-2"></i>
                                </div>
                                <div class="flex-grow-1">
                                    {{ trans('whitelabellead::template.common.ok') }}
                                </div>
                            </div>
                        </button>
                    </div>
				</div>
            </div>
        </div>
    </div>
</div>

<div class="new_modal modal fade" id="noSelectedRecords" tabindex="-1" role="basic" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">{{ trans('whitelabellead::template.common.alert') }}</h5>
                <button type="button" class="btn-close fs-10" data-bs-dismiss="modal" aria-label="Close"> </button>
            </div>
            <div class="modal-body text-center">
                <h5 class="mb-2">{{ trans('whitelabellead::template.WhiteLabelLeadModule.leastRecord') }}</h5>
                <div class="pt-2">
                    <button type="button" class="btn btn-primary bg-gradient waves-effect waves-light btn-label" data-bs-dismiss="modal">
                        <div class="d-flex">
                            <div class="flex-shrink-0">
                                <i class="ri-check-line label-icon align-middle fs-20 me-2"></i>
                            </div>
                            <div class="flex-grow-1">
                                {{ trans('whitelabellead::template.common.ok') }}
                            </div>
                        </div>
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>


@endsection


@section('scripts')
<script type="text/javascript">
    window.site_url = '{!! url("/") !!}';
    var DELETE_URL = '{!! url("/powerpanel/white-label/DeleteRecord") !!}';

    var showChecker = true;
    @if (!$userIsAdmin)
    showChecker = false;
    @endif
</script>

<script src="{{ $CDN_PATH.'resources/global/plugins/jquery-cookie-master/src/jquery.cookie.js' }}" type="text/javascript"></script>
<script src="{{ $CDN_PATH.'resources/global/scripts/datatable.js' }}" type="text/javascript"></script>
<script src="{{ $CDN_PATH.'resources/global/plugins/datatables/datatables.min.js' }}" type="text/javascript"></script>
<script src="{{ $CDN_PATH.'resources/global/plugins/datatables/plugins/bootstrap/datatables.bootstrap.js' }}" type="text/javascript"></script>
<script src="{{ $CDN_PATH.'resources/pages/scripts/packages/whitelabel/whitelabellead-datatables-ajax.js?v='.time() }}" type="text/javascript"></script>
@if((File::exists(app_path() . '/Workflow.php') != null || File::exists(base_path() . '/packages/Powerpanel/Workflow/src/Models/Workflow.php') != null))
    <script src="{{ $CDN_PATH.'resources/pages/scripts/user-updates-approval.js' }}" type="text/javascript"></script>
@endif
<script src="{{ $CDN_PATH.'resources/global/plugins/highslide/highslide-with-html.js' }}" type="text/javascript"></script>
<script src="{{ $CDN_PATH.'resources/pages/scripts/custom.js' }}" type="text/javascript"></script>
<script src="{{ $CDN_PATH.'resources/pages/scripts/sharer-validations.js' }}" type="text/javascript"></script>


@if(Auth::user()->hasRole('user_account'))
<script type="text/javascript">
    $(document).ready(function () {
        setInterval(function () {
            $('.checker').closest("td").hide();
            $('.checker').closest("th").hide();
        }, 800);
    });
</script>
@endif
<script type="text/javascript">
    $(document).ready(function () {
        $('#start_date').flatpickr({
            dateFormat: DEFAULT_DATE_FORMAT,
        });
        $('#start_date').on('change', function (e) {
	        let index = e.target.getAttribute("data-dateIndex");
	        let date = new Date(e.target.value)
	        $('#end_date').flatpickr({
	            dateFormat: DEFAULT_DATE_FORMAT,
	            minDate: date
	        }).clear();
      	});

        $('#end_date').flatpickr({
            dateFormat: DEFAULT_DATE_FORMAT,
            minDate: 'today'
        });
    });
</script>

@endsection