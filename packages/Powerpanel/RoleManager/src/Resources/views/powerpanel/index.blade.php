{{-- @extends('powerpanel.layouts.app')
@section('title')
{{Config::get('Constant.SITE_NAME')}} - PowerPanel
@endsection
@section('css')
<link href="{{ $CDN_PATH.'resources/global/plugins/datatables/datatables.min.css' }}" rel="stylesheet" type="text/css" />
<link href="{{ $CDN_PATH.'resources/global/plugins/datatables/plugins/bootstrap/datatables.bootstrap.css' }}" rel="stylesheet" type="text/css" />
<link href="{{ $CDN_PATH.'resources/global/plugins/bootstrap-datepicker/css/bootstrap-datepicker3.min.css' }}" rel="stylesheet" type="text/css" />
@endsection
@section('content')
@include('powerpanel.partials.breadcrumbs')

<div class="row">
    <div class="col-md-12">
        @if(Session::has('message'))
            <div class="alert alert-success">
                {{ Session::get('message') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        <div class="card">
            <div class="card-header border-0">
                <div class="d-flex align-items-center">
                    <h5 class="card-title mb-0 flex-grow-1">Roles</h5>
                    <div class="flex-shrink-0">
                        @can('roles-create')
                        <a class="btn btn-primary add-btn add_category" href="{{ route('powerpanel.roles.add') }}"><span>{{ trans('rolemanager::template.roleModule.createRole') }}</span> <i class="ri-add-line"></i></a>
                        @endcan
                    </div>
                </div>
            </div><!-- end card header -->

            <div class="card-body border border-dashed border-end-0 border-start-0">
                <div class="row g-3">
                    <div class="col-xxl-4 col-sm-6">
                        <div class="search-box search_rh_div">
                            <input type="search" class="form-control search" placeholder="Search by Name" id="searchfilter">
                            <i class="ri-search-line search-icon"></i>
                        </div>
                    </div>
                    <!--end col-->
                </div><!--end row-->
            </div>

            @if($iTotalRecords > 0)
                <div class="card-body">
                    <div class="live-preview">
                        <div class="table-responsive">
                            <table class="table align-middle table-nowrap hide-mobile dataTable no-footer hide-mobile" id="datatable_ajax">
                                <thead class="text-muted table-light">
                                    <tr role="row" class="text-uppercase heading">
                                        <th width="3%" align="center"><input type="checkbox" class="group-checkable form-check-input"></th>
                                        <th width="40%" align="left">{{ trans('rolemanager::template.common.name') }}</th>
                                        <th width="20%" align="center">Admin / User</th>
                                        <th width="20%" align="center">Modified Date</th>
                                        <th width="17%" align="right">{{ trans('rolemanager::template.common.actions') }}</th>
                                    </tr>
                                </thead>
                                <tbody></tbody>
                            </table>
                            @can('roles-delete')
                                <a href="javascript:void(0);" class="btn rounded-pill btn-danger waves-effect right_bottom_btn hide-btn-mob deleteMass">Delete</a>
                            @endcan
                        </div>
                    </div>
                </div><!-- end card-body -->
            @else
                @include('powerpanel.partials.addrecordsection',['type'=>Config::get('Constant.MODULE.TITLE'), 'adUrl' => url('powerpanel/roles/add')])
            @endif
        </div><!-- end card -->
    </div>
</div>
<div class="clearfix"></div>
<!-- /.modal -->
@include('powerpanel.partials.deletePopup')
@endsection
@section('scripts')
<script type="text/javascript">
    window.site_url = '{!! url("/") !!}';
    var DELETE_URL = '{!! url("/powerpanel/roles/DeleteRecord") !!}';
</script>
<script src="{{ $CDN_PATH.'resources/global/plugins/jquery-cookie-master/src/jquery.cookie.js' }}" type="text/javascript"></script>
<script src="{{ $CDN_PATH.'resources/global/scripts/datatable.js' }}" type="text/javascript"></script>
<script src="{{ $CDN_PATH.'resources/global/plugins/datatables/datatables.min.js' }}" type="text/javascript"></script>
<script src="{{ $CDN_PATH.'resources/global/plugins/datatables/plugins/bootstrap/datatables.bootstrap.js' }}" type="text/javascript"></script>
<script src="{{ $CDN_PATH.'resources/global/plugins/bootstrap-datepicker/js/bootstrap-datepicker.min.js' }}" type="text/javascript"></script>
<script src="{{ $CDN_PATH.'resources/pages/scripts/packages/rolemanager/table-role_manager-ajax.js' }}" type="text/javascript"></script>
<script src="{{ $CDN_PATH.'resources/pages/scripts/custom.js' }}" type="text/javascript"></script>
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
                            @can('roles-create')
                                <a class="btn btn-light btn-theme bg-gradient waves-effect waves-light btn-label" href="{{ route('powerpanel.roles.add') }}">
                                    <div class="d-flex">
                                        <div class="flex-shrink-0">
                                            <i class="ri-add-line label-icon align-middle fs-20 me-2"></i>
                                        </div>
                                        <div class="flex-grow-1">
                                            {{ trans('rolemanager::template.roleModule.createRole') }}
                                        </div>
                                    </div>
                                </a>
                            @endcan
                        </div>

                        <div class="cm-filter flex-grow-1 order-sm-1 d-flex align-items-center">
                            @if (File::exists(base_path() . '/resources/views/powerpanel/partials/tabpanel.blade.php') != null)
                                {{-- @include('powerpanel.partials.tabpanel',['tabarray'=>[]]) --}}
                            @endif
                            {{-- <div class="btn-group d-inline-block filter-dropdown">
                                <button type="button" class="btn fs-14 fw-medium p-0 border-0 filter-btn" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><i class="ri-filter-line fs-21"></i></button>
                                <div class="dropdown-menu">
                                    <div class="p-3 dropdown-body">
                                        <div class="mb-3" id="hidefilter">
                                            <select class="form-select" id="statusfilter">
                                                <option value="">{!! trans('blogs::template.common.selectstatus') !!}</option>
                                                <option value="Y">{!! trans('blogs::template.common.publish') !!}</option>
                                                <option value="N">{!! trans('blogs::template.common.unpublish') !!}</option>
                                            </select>
                                        </div>
                                        @if($userIsAdmin)
                                            <div class="mb-3">
                                                <select class="form-select" id="sectorfilter">
                                                    <option value="">{{ trans('Select Sector') }}</option>
                                                    @if(!empty($sectorList))
                                                        @foreach($sectorList as $key =>  $ValueSector)
                                                            <option value="{{ $key }}">{{ $ValueSector }}</option>
                                                        @endforeach
                                                    @endif
                                                </select>
                                            </div>
                                        @endif
                                        <div class="mb-3">
                                            <select class="form-select" id="category" name="category">
                                                <option value="">Select Category</option>
                                                @if(!empty($blogCategory))
                                                    @foreach ($blogCategory as $cat)
                                                        @php $permissionName = 'faq-list' @endphp
                                                        @php $selected = ''; @endphp
                                                        @if(isset($blog->intFKCategory))
                                                            @if($cat['id'] == $blog->intFKCategory)
                                                                @php $selected = 'selected'; @endphp
                                                            @endif
                                                        @endif
                                                        <option value="{{ $cat['id'] }}" {{ $selected }} >{{ $cat['varModuleName']== "managementteam"?'Select Category':$cat['varTitle'] }}</option>
                                                    @endforeach
                                                @endif
                                            </select>
                                        </div>

                                        <div class="reset-btn">
                                            <button type="button" class="btn btn-light bg-gradient waves-effect waves-light btn-light btn-label" title="Reset" id="refresh">
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
                            </div> --}}
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
                                        ['Identity_Name'=>'user','TabIndex'=>'2','Name'=>'Admin / User'],
                                        ['Identity_Name'=>'date','TabIndex'=>'3','Name'=>'Modified Date'],
                                        ['Identity_Name'=>'dactions','TabIndex'=>'4','Name'=>'Action']
                                    ],
                                    'DataTableHead'=>[
                                        ['Title'=>'Name','Align'=>'left'],
                                        ['Title'=>'Admin / User','Align'=>'left'],
                                        ['Title'=>'Modified Date','Align'=>'left'],
                                        ['Title'=>'Action','Align'=>'right']
                                    ]
                                ]
                            ];
                            @endphp
                            @include('powerpanel.partials.datatable-view',['ModuleName'=>'Roles','Permission_Delete'=>'roles-delete','tablearray'=>$tablearray,'userIsAdmin'=>$userIsAdmin,'Module_ID'=>Config::get('Constant.MODULE.ID')])
                        </div>
                    </div>
                </div><!-- end card-body -->

                <!-- Modal -->
                @if (File::exists(base_path() . '/resources/views/powerpanel/partials/quickeditpopup.blade.php') != null)
                @include('powerpanel.partials.quickeditpopup',['TableName'=>'roles'])
                @endif
                @else
                @if (File::exists(base_path() . '/resources/views/powerpanel/partials/addrecordsection.blade.php') != null)
                @include('powerpanel.partials.addrecordsection',['type'=>Config::get('Constant.MODULE.TITLE'), 'adUrl' => url('powerpanel/roles/add')])
                @endif
            @endif

        </div><!-- end card -->
    </div>
    <!-- end col -->
</div>


@if (File::exists(base_path() . '/resources/views/powerpanel/partials/deletePopup.blade.php') != null)
@include('powerpanel.partials.deletePopup')
@endif

@endsection


@section('scripts')
<script type="text/javascript">
    window.site_url = '{!! url("/") !!}';
    var DELETE_URL = '{!! url("/powerpanel/roles/DeleteRecord") !!}';

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
<script src="{{ $CDN_PATH.'resources/pages/scripts/packages/rolemanager/table-role_manager-ajax.js?v='.time() }}" type="text/javascript"></script>
<script src="{{ $CDN_PATH.'resources/pages/scripts/custom.js' }}" type="text/javascript"></script>


@if(Auth::user()->hasRole('user_account'))
<script type="text/javascript">
    $(document).ready(function () {
        setInterval(function () {
            $('.checker').closest("td").hide();
            $('.checker').closest("th").hide();
        }, 800);
    });
    var moduleName = 'roles';</script>
@endif

@endsection