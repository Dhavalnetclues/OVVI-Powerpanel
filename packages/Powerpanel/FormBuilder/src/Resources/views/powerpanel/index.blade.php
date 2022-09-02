{{-- @extends('powerpanel.layouts.app')
@section('title')
{{Config::get('Constant.SITE_NAME')}} - PowerPanel
@stop
@section('css')
<link href="{{ $CDN_PATH.'resources/global/plugins/datatables/datatables.min.css' }}" rel="stylesheet" type="text/css" />
<link href="{{ $CDN_PATH.'resources/global/plugins/datatables/plugins/bootstrap/datatables.bootstrap.css' }}" rel="stylesheet" type="text/css" />
<link href="{{ $CDN_PATH.'resources/global/plugins/highslide/highslide.css' }}" rel="stylesheet" type="text/css"/>
<link href="{{ $CDN_PATH.'resources/global/css/rank-button.css' }}" rel="stylesheet" type="text/css" />
@endsection
@section('content')
<!--@include('powerpanel.partials.breadcrumbs')-->
<!-- BEGIN PAGE BASE CONTENT -->
{!! csrf_field() !!}
<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-header border-0">
                <div class="d-flex align-items-center">
                    <h5 class="card-title mb-0 flex-grow-1"></h5>
                    <div class="flex-shrink-0">
                        @can('formbuilder-create')
                            <a class="btn btn-primary add-btn add_category" href="{{ url('powerpanel/formbuilder/add') }}"><span>ADD Form</span> <i class="ri-add-line"></i></a>
                        @endcan
                    </div>
                </div>
            </div><!-- end card header -->
            <div class="card-body border border-dashed border-end-0 border-start-0">
                <div class="row">
                    <div class="col-lg-2 col-md-4 col-xs-12" id="hidefilter">
                        <div class="search-box">
                            <input type="search" class="form-control search" placeholder="Search by User" id="searchfilter">
                            <i class="ri-search-line search-icon"></i>
                        </div>
                    </div>
                    <div class="col-xxl-2 col-sm-4" id="hidefilter">
                        <select class="form-control" id="statusfilter" data-choices data-choices-search-false>
                            <option value="">{!! trans('formbuilder::template.common.selectstatus') !!}</option>
                            <option value="Y">{!! trans('formbuilder::template.common.publish') !!}</option>
                            <option value="N">{!! trans('formbuilder::template.common.unpublish') !!}</option>
                        </select>
                    </div>
                    <div class="col-lg-2 col-sm-2">
                        <button type="button" class="btn btn-primary" title="Reset" id="refresh">
                            <i class="ri-refresh-line"></i>
                        </button>
                    </div>
                    <div class="col-lg-2 col-sm-2">
                        <div class="add_category_button pull-right">
                            <a title="Help" class="add_category" target="_blank" href="{{ $CDN_PATH.'assets/videos/Shield_CMS_WorkFlow.mp4'}}">
                                <span title="Help">Help</span> <i class="la la-question-circle"></i>
                            </a>
                        </div>
                    </div>
                </div>
            </div>

            @if($iTotalRecords > 0)
                <div class="card-body">
                    <div class="live-preview">
                        <div class="table-responsive">
                            @if(Session::has('message'))
                                <div class="alert alert-success">
                                    {{ Session::get('message') }}
                                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                                </div>
                            @endif

                            <table class="table table-striped table-bordered table-hover table-checkable hide-mobile table-card" id="datatable_ajax">
                                <thead class="text-muted table-light">
                                    <tr role="row" class="heading">
                                        <th width="2%" align="center"><input type="checkbox" class="form-check-input group-checkable"></th>
                                        <th width="20%" align="left">Form Name</th>
                                        <th width="10%" align="center">Email Information</th>
                                        <th width="20%" align="center">{{ trans('formbuilder::template.common.publish') }}</th>
                                        <th width="20%" align="center">Date</th>
                                        <th width="15%" align="right">{{ trans('formbuilder::template.common.actions') }}</th>
                                    </tr>
                                </thead>
                                <tbody></tbody>
                            </table>
                            <a href="javascript:void(0);" class="btn rounded-pill btn-danger waves-effect right_bottom_btn deleteMass">{{ trans('formbuilder::template.common.delete') }}
                            </a>
                        </div>
                    </div>
                </div><!-- end card-body -->
            @else
            @include('powerpanel.partials.addrecordsection',['type'=>Config::get('Constant.MODULE.TITLE'), 'adUrl' => url('powerpanel/formbuilder/add')])
            @endif
        </div><!-- end card -->
    </div>
</div>
@include('powerpanel.partials.deletePopup')
@include('powerpanel.partials.approveRecord')
@include('powerpanel.partials.cmsPageComments',['module'=>Config::get('Constant.MODULE.TITLE')])
@endsection
@section('scripts')
<script type="text/javascript">
    window.site_url = '{!! url("/") !!}';
    var DELETE_URL = '{!! url("/powerpanel/formbuilder/DeleteRecord") !!}';
    var APPROVE_URL = '{!! url("/powerpanel/formbuilder/ApprovedData_Listing") !!}';
    var getChildData = window.site_url + "/powerpanel/formbuilder/getChildData";
    var getChildData_rollback = window.site_url + "/powerpanel/formbuilder/getChildData_rollback";
    var ApprovedData_Listing = window.site_url + "/powerpanel/formbuilder/ApprovedData_Listing";
    var Get_Comments = '{!! url("/powerpanel/formbuilder/Get_Comments") !!}';
    var Quick_module_id = '<?php echo Config::get('Constant.MODULE.ID'); ?>';
    var showChecker = true;
            @if (!$userIsAdmin)
            showChecker = false;
            @endif
</script>
<script src="{{ $CDN_PATH.'resources/global/plugins/jquery-cookie-master/src/jquery.cookie.js' }}" type="text/javascript"></script>
<script src="{{ $CDN_PATH.'resources/global/scripts/datatable.js' }}" type="text/javascript"></script>
<script src="{{ $CDN_PATH.'resources/global/plugins/datatables/datatables.min.js' }}" type="text/javascript"></script>
<script src="{{ $CDN_PATH.'resources/global/plugins/datatables/dataTables.editor.js' }}" type="text/javascript"></script>
<script src="{{ $CDN_PATH.'resources/global/plugins/datatables/plugins/bootstrap/datatables.bootstrap.js' }}" type="text/javascript"></script>

<script src="{{ $CDN_PATH.'resources/pages/scripts/packages/formbuilder/formbuilder-datatables-ajax.js' }}" type="text/javascript"></script>
<script src="{{ $CDN_PATH.'resources/pages/scripts/custom.js' }}" type="text/javascript"></script>
<script src="{{ $CDN_PATH.'resources/global/plugins/highslide/highslide-with-html.js' }}" type="text/javascript"></script>
<script src="{{ $CDN_PATH.'resources/pages/scripts/table-grid-quick-fun-ajax.js' }}" type="text/javascript"></script>
<script type="text/javascript">
    $(document).ready(function () {
        setInterval(function () {
            $('.addhiglight').closest("td").closest("tr").addClass('higlight');
        }, 800);
    });
</script>
@endsection --}}


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
                            <div class="add_category_button d-inline-block me-2">
                                <a title="Help" class="add_category fs-14" target="_blank" href="{{ $CDN_PATH.'assets/videos/Shield_CMS_WorkFlow.mp4'}}">
                                    <span title="Help">Help</span> <i class="ri-question-line fs-16"></i>
                                </a>
                            </div>
                            @can('formbuilder-create')
                                <a class="btn btn-light btn-theme bg-gradient waves-effect waves-light btn-label" href="{{ url('powerpanel/formbuilder/add') }}">
                                    <div class="d-flex">
                                        <div class="flex-shrink-0">
                                            <i class="ri-add-line label-icon align-middle fs-20 me-2"></i>
                                        </div>
                                        <div class="flex-grow-1">
                                            Add Form
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
                                                <option value="">{!! trans('formbuilder::template.common.selectstatus') !!}</option>
                                                <option value="Y">{!! trans('formbuilder::template.common.publish') !!}</option>
                                                <option value="N">{!! trans('formbuilder::template.common.unpublish') !!}</option>
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
                                <input type="search" class="form-control search" placeholder="Search by Title" id="searchfilter">
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
                                            ['Identity_Name'=>'title','TabIndex'=>'1','Name'=>'Title'],
                                            ['Identity_Name'=>'email','TabIndex'=>'2','Name'=>'Email'],
                                            ['Identity_Name'=>'publish','TabIndex'=>'3','Name'=>'Publish'],
                                            ['Identity_Name'=>'date','TabIndex'=>'4','Name'=>'Date'],
                                            ['Identity_Name'=>'dactions','TabIndex'=>'5','Name'=>'Action']
                                        ],
                                        'DataTableHead'=>[
                                            ['Title'=>'Title','Align'=>'left'],
                                            ['Title'=>'Email','Align'=>'left'],
                                            ['Title'=>'Publish','Align'=>'left'],
                                            ['Title'=>'Date','Align'=>'left'],
                                            ['Title'=>'Action','Align'=>'right']
                                        ]
                                    ]
                                ];
                            @endphp
                            @include('powerpanel.partials.datatable-view',['ModuleName'=>'FormBuilder','Permission_Delete'=>'formbuilder-delete','tablearray'=>$tablearray,'userIsAdmin'=>$userIsAdmin,'Module_ID'=>Config::get('Constant.MODULE.ID')])
                        </div>
                    </div>
                </div><!-- end card-body -->

                <!-- Modal -->
                @if (File::exists(base_path() . '/resources/views/powerpanel/partials/quickeditpopup.blade.php') != null)
                @include('powerpanel.partials.quickeditpopup',['TableName'=>'formBuilder'])
                @endif
                @else
                @if (File::exists(base_path() . '/resources/views/powerpanel/partials/addrecordsection.blade.php') != null)
                @include('powerpanel.partials.addrecordsection',['type'=>Config::get('Constant.MODULE.TITLE'), 'adUrl' => url('powerpanel/formbuilder/add')])
                @endif
            @endif

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
    var DELETE_URL = '{!! url("/powerpanel/formbuilder/DeleteRecord") !!}';
    var APPROVE_URL = '{!! url("/powerpanel/formbuilder/ApprovedData_Listing") !!}';
    var getChildData = window.site_url + "/powerpanel/formbuilder/getChildData";
    var getChildData_rollback = window.site_url + "/powerpanel/formbuilder/getChildData_rollback";
    var ApprovedData_Listing = window.site_url + "/powerpanel/formbuilder/ApprovedData_Listing";
    var Get_Comments = '{!! url("/powerpanel/formbuilder/Get_Comments") !!}';
    var Quick_module_id = '<?php echo Config::get('Constant.MODULE.ID'); ?>';
    var showChecker = true;
    @if (!$userIsAdmin)
    showChecker = false;
    @endif

    $(document).ready(function () {
        var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
        var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
        return new bootstrap.Tooltip(tooltipTriggerEl)
        })
    });
</script>

<script src="{{ $CDN_PATH.'resources/global/plugins/jquery-cookie-master/src/jquery.cookie.js' }}" type="text/javascript"></script>
<script src="{{ $CDN_PATH.'resources/global/scripts/datatable.js' }}" type="text/javascript"></script>
<script src="{{ $CDN_PATH.'resources/global/plugins/datatables/datatables.min.js' }}" type="text/javascript"></script>
<script src="{{ $CDN_PATH.'resources/global/plugins/datatables/plugins/bootstrap/datatables.bootstrap.js' }}" type="text/javascript"></script>
<script src="{{ $CDN_PATH.'resources/pages/scripts/packages/formbuilder/formbuilder-datatables-ajax.js?v='.time() }}" type="text/javascript"></script>
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
    var moduleName = 'formbuilder';</script>
@endif

@endsection