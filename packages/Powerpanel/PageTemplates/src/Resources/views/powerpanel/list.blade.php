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

                        <div class="flex-shrink-0 addpage-btn order-sm-2">
                            @can('page_template-create')
                                <a class="btn btn-light btn-theme bg-gradient waves-effect waves-light btn-label" href="{{ url('powerpanel/page_template/add') }}">
                                    <div class="d-flex">
                                        <div class="flex-shrink-0">
                                            <i class="ri-add-line label-icon align-middle fs-20 me-2"></i>
                                        </div>
                                        <div class="flex-grow-1">
                                            Add Page Template
                                        </div>
                                    </div>
                                </a>
                            @endcan
                        </div>

                        <div class="cm-filter flex-grow-1 order-sm-1 d-flex align-items-center">
                            @if (File::exists(base_path() . '/resources/views/powerpanel/partials/tabpanel.blade.php') != null)
                                {{-- @include('powerpanel.partials.tabpanel',['tabarray'=>[]]) --}}
                            @endif
                            <div class="btn-group d-inline-block filter-dropdown">
                                <button type="button" class="btn fs-14 fw-medium p-0 border-0 filter-btn" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><i class="ri-filter-line fs-21"></i></button>
                                <div class="dropdown-menu">
                                    <div class="p-3 dropdown-body">
                                        <div class="mb-3" id="hidefilter">
                                            <select class="form-select" id="statusfilter">
                                                <option value="">{!! trans('pagetemplates::template.common.selectstatus') !!}</option>
                                                <option value="Y">{!! trans('pagetemplates::template.common.publish') !!}</option>
                                                <option value="N">{!! trans('pagetemplates::template.common.unpublish') !!}</option>
                                            </select>
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
                                <input type="search" class="form-control search" placeholder="Search by Template" id="searchfilter">
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
                                        ['Identity_Name'=>'title','TabIndex'=>'1','Name'=>'Template'],
                                        ['Identity_Name'=>'date','TabIndex'=>'2','Name'=>'Date'],
                                        ['Identity_Name'=>'publish','TabIndex'=>'3','Name'=>'Publish'],
                                        ['Identity_Name'=>'dactions','TabIndex'=>'4','Name'=>'Action']
                                    ],
                                    'DataTableHead'=>[
                                        ['Title'=>'Template','Align'=>'left'],
                                        ['Title'=>'Date','Align'=>'left'],
                                        ['Title'=>'Publish','Align'=>'left'],
                                        ['Title'=>'Action','Align'=>'right']
                                    ]
                                ]
                            ];
                            @endphp
                            @include('powerpanel.partials.datatable-view',['ModuleName'=>'PageTemplate','Permission_Delete'=>'page_template-delete','tablearray'=>$tablearray,'userIsAdmin'=>$userIsAdmin,'Module_ID'=>Config::get('Constant.MODULE.ID')])
                        </div>
                    </div>
                </div><!-- end card-body -->

                <!-- Modal -->
                @if (File::exists(base_path() . '/resources/views/powerpanel/partials/quickeditpopup.blade.php') != null)
                @include('powerpanel.partials.quickeditpopup',['TableName'=>'page_template'])
                @endif
            @else
                @if (File::exists(base_path() . '/resources/views/powerpanel/partials/addrecordsection.blade.php') != null)
                @include('powerpanel.partials.addrecordsection',['type'=>Config::get('Constant.MODULE.TITLE'), 'adUrl' => url('powerpanel/page_template/add')])
                @endif
            @endif

            {{-- <div class="new_modal modal fade" style="display: none" id="modalForm" role="dialog">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <!-- Modal Header -->
                        <div class="modal-header">
                            <h4 class="modal-title" id="myModalLabel">Quick Edit</h4>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <!-- Modal Body -->
                        {!! Form::open(['method' => 'post','class'=>'QuickEditForm','id'=>'QuickEditForm']) !!}
                        {!! Form::hidden('id','',array('id' => 'id')) !!}
                        {!! Form::hidden('quickedit','',array('id' => 'quickedit')) !!}
                        <div class="modal-body form_pattern">
                            <div class="mb-3">
                                <label for="name">Name <span aria-required="true" class="required"> * </span></label>
                                {!! Form::text('name',  old('name') , array('id' => 'name', 'class' => 'form-control', 'placeholder'=>'Enter your name')) !!}
                            </div>
                            <div class="row">
                                <div class="col-md-12">
                                    <label class="form_title">Search Ranking</label>
                                    <a href="javascript:void(0);" data-bs-toggle="tooltip" class="config" data-bs-placement="bottom" data-bs-content="{{ trans('pagetemplates::template.common.SearchEntityTools') }}" title="{{ trans('pagetemplates::template.common.SearchEntityTools') }}"><i class="fa fa-question"></i></a>
                                    <div class="wrapper search_rank">
                                        <label for="yes_radio" id="yes-lbl">High</label><input type="radio" value="1" name="search_rank" id="yes_radio">
                                        <label for="maybe_radio" id="maybe-lbl">Medium</label><input type="radio" value="2" name="search_rank" id="maybe_radio">
                                        <label for="no_radio" id="no-lbl">Low</label><input type="radio" value="3" name="search_rank" id="no_radio">
                                        <div class="toggle"></div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3 form-md-line-input">
                                        <label class="control-label form_title">{{ trans('pagetemplates::template.common.startDateAndTime') }} <span aria-required="true" class="required"> * </span></label>
                                        <div class="input-group date form_meridian_datetime" data-date="{{ Carbon\Carbon::today()->format('Y-m-d') }}T15:25:00Z">
                                            <span class="input-group-btn date_default">
                                                <i class="ri-calendar-line"></i>
                                            </span>
                                            {!! Form::text('start_date_time', isset($Cmspage->dtDateTime)?date('Y-m-d H:i',strtotime($Cmspage->dtDateTime)):date('Y-m-d H:i'), array('class' => 'form-control', 'data-provider' => 'flatpickr', 'data-date-format' => Config::get('Constant.DEFAULT_DATE_FORMAT'), 'data-enable-time' => '','maxlength'=>160,'size'=>'16','id'=>'start_date_time','autocomplete'=>'off','onkeypress'=>"javascript: return KeycheckOnlyDate(event);",'onpaste'=>'return false')) !!}
                                        </div>

                                        @php
                                        if (isset($Cmspage_highLight->dtDateTime) && $Cmspage_highLight->dtDateTime != $Cmspage->dtDateTime) {
                                                $Class_date = ' highlitetext';
                                            } else {
                                                $Class_date = '';
                                        } @endphp
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3 form-md-line-input">
                                        <div class="input-group date form_meridian_datetime expirydate" data-date="{{ Carbon\Carbon::today()->format('Y-m-d') }}T15:25:00Z">
                                            <label class="control-label form_title" >{{ trans('pagetemplates::template.common.endDateAndTime') }} <span aria-required="true" class="required"> * </span></label>
                                            <div class="pos_cal">
                                                <span class="input-group-btn date_default">
                                                    <i class="ri-calendar-line"></i>
                                                </span>
                                                {!! Form::text('end_date_time', isset($Cmspage->dtEndDateTime)?date('Y-m-d H:i',strtotime($Cmspage->dtEndDateTime)):date('Y-m-d H:i'), array('class' => 'form-control', 'data-provider' => 'flatpickr', 'data-date-format' => Config::get('Constant.DEFAULT_DATE_FORMAT'), 'data-enable-time' => '','maxlength'=>160,'size'=>'16','id'=>'end_date_time','data-exp'=> '','data-newvalue','autocomplete'=>'off','onkeypress'=>"javascript: return KeycheckOnlyDate(event);",'onpaste'=>'return false')) !!}
                                            </div>
                                        </div>
                                        <label class="expdatelabel">
                                            <a id="noexpiry" name="noexpiry" href="javascript:void(0);">
                                                <b class="expiry_lbl"></b>
                                            </a>
                                        </label>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="submit" class="btn btn-primary" id="quick_submit" value="saveandexit">Submit</button>
                        </div>
                        {!! Form::close() !!}
                    </div>
                </div>
            </div> --}}

        </div><!-- end card -->
    </div>
    <!-- end col -->
</div>


@if (File::exists(base_path() . '/resources/views/powerpanel/partials/deletePopup.blade.php') != null)
@include('powerpanel.partials.deletePopup')
@endif
@if (File::exists(base_path() . '/resources/views/powerpanel/partials/onepushmodal.blade.php') != null)
@include('powerpanel.partials.onepushmodal',['moduleHasImage'=>false])
@endif
@if (File::exists(base_path() . '/resources/views/powerpanel/partials/approveRecord.blade.php') != null)
@include('powerpanel.partials.approveRecord')
@endif
@if (File::exists(base_path() . '/resources/views/powerpanel/partials/cmsPageComments.blade.php') != null)
@include('powerpanel.partials.cmsPageComments',['module'=>Config::get('Constant.MODULE.TITLE')])
@endif
@endsection


@section('scripts')
<script type="text/javascript">
    window.site_url = '{!! url("/") !!}';
    var DELETE_URL = '{!! url("/powerpanel/page_template/DeleteRecord") !!}';
    var APPROVE_URL = '{!! url("/powerpanel/page_template/ApprovedData_Listing") !!}';
    $(document).ready(function () {
        var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
        var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
            return new bootstrap.Tooltip(tooltipTriggerEl)
        })
    });
    var APPROVE_URL = '{!! url("/powerpanel/page_template/ApprovedData_Listing") !!}';
    var getChildData = window.site_url + "/powerpanel/page_template/getChildData";
    var getChildData_rollback = window.site_url + "/powerpanel/page_template/getChildData_rollback";
    var ApprovedData_Listing = window.site_url + "/powerpanel/page_template/ApprovedData_Listing";
    var Get_Comments = window.site_url + "/powerpanel/page_template/Get_Comments";

    var showChecker = true;
            @if (!$userIsAdmin)
            showChecker = false;
            @endif
</script>

<script src="{{ $CDN_PATH.'resources/global/plugins/jquery-cookie-master/src/jquery.cookie.js' }}" type="text/javascript"></script>
<script src="{{ $CDN_PATH.'resources/global/scripts/datatable.js' }}" type="text/javascript"></script>
<script src="{{ $CDN_PATH.'resources/global/plugins/datatables/datatables.min.js' }}" type="text/javascript"></script>
<script src="{{ $CDN_PATH.'resources/global/plugins/datatables/plugins/bootstrap/datatables.bootstrap.js' }}" type="text/javascript"></script>
<script src="{{ $CDN_PATH.'resources/pages/scripts/packages/pagetemplates/table-pages-template-ajax.js?v='.time() }}" type="text/javascript"></script>
@if((File::exists(app_path() . '/Workflow.php') != null || File::exists(base_path() . '/packages/Powerpanel/Workflow/src/Models/Workflow.php') != null))
    <script src="{{ $CDN_PATH.'resources/pages/scripts/user-updates-approval.js' }}" type="text/javascript"></script>
@endif
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
    var moduleName = 'page_template';</script>
@endif

@endsection