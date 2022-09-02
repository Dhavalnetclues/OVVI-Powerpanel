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
        <div class="card">
        	@if($iTotalRecords > 0)

                <div class="card-header cmpage-topheader border border-dashed border-end-0 border-start-0 border-top-0">
                    <div class="d-xl-flex flex-wrap align-items-center">

                        <div class="flex-shrink-0 addpage-btn order-sm-2">
                            @can('contact-info-create')
                                <a class="btn btn-light btn-theme bg-gradient waves-effect waves-light btn-label" href="{{ url('powerpanel/contact-info/add') }}">
                                    <div class="d-flex">
                                        <div class="flex-shrink-0">
                                            <i class="ri-add-line label-icon align-middle fs-20 me-2"></i>
                                        </div>
                                        <div class="flex-grow-1">
                                            {{ trans('contactinfo::template.contactModule.addnewcontact') }}
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
                                                <option value="">{{ trans('contactinfo::template.common.selectstatus') }}</option>
                                                <option value="Y">{{ trans('contactinfo::template.common.publish') }}</option>
                                                <option value="N">{{ trans('contactinfo::template.common.unpublish') }}</option>
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
                                <input type="search" class="form-control search" placeholder="Search by Name" id="searchfilter">
                                <span class="iconsearch cursor-pointer"><i class="ri-search-2-line fs-21"></i></span>
                            </div>
                        </div>

                    </div>
                </div><!-- end card header -->

                <div class="card-body">
                    <div class="live-preview">
                        <div class="table-responsive">
                            @if(Session::has('message'))
                                <div class="alert alert-success alert-dismissible fade show" role="alert">
                                    {{ Session::get('message') }}
                                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                                </div>
                            @endif
                            @php
                                $tablearray = [
                                    'DataTableTab'=>[
                                        'ColumnSetting'=>[
                                            ['Identity_Name'=>'name','TabIndex'=>'1','Name'=>'Name'],
                                            ['Identity_Name'=>'email','TabIndex'=>'2','Name'=>'Email'],
                                            ['Identity_Name'=>'email','TabIndex'=>'3','Name'=>'Mailing Address'],
                                            ['Identity_Name'=>'email','TabIndex'=>'4','Name'=>'Date & Time'],
                                            ['Identity_Name'=>'publish','TabIndex'=>'5','Name'=>'Publish'],
                                            ['Identity_Name'=>'dactions','TabIndex'=>'6','Name'=>'Action']
                                        ],
                                        'DataTableHead'=>[
                                            ['Title'=>'Name','Align'=>'left'],
                                            ['Title'=>'Email','Align'=>'left'],
                                            ['Title'=>'Mailing Address','Align'=>'left'],
                                            ['Title'=>'Date & Time','Align'=>'left'],
                                            ['Title'=>'Publish','Align'=>'left'],
                                            ['Title'=>'Action','Align'=>'right']
                                        ]
                                    ]
                                ];
                            @endphp
                            @include('powerpanel.partials.datatable-view',['ModuleName'=>'ContactInfo','Permission_Delete'=>'contact-info-delete','tablearray'=>$tablearray,'userIsAdmin'=>$userIsAdmin,'Module_ID'=>Config::get('Constant.MODULE.ID')])
                        </div>
                    </div>
                </div><!-- end card-body -->
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
    var DELETE_URL = '{!! url("/powerpanel/contact-info/DeleteRecord") !!}';
</script>

<script src="{{ $CDN_PATH.'resources/global/plugins/jquery-cookie-master/src/jquery.cookie.js' }}" type="text/javascript"></script>
<script src="{{ $CDN_PATH.'resources/global/scripts/datatable.js' }}" type="text/javascript"></script>
<script src="{{ $CDN_PATH.'resources/global/plugins/datatables/datatables.min.js' }}" type="text/javascript"></script>
<script src="{{ $CDN_PATH.'resources/global/plugins/datatables/plugins/bootstrap/datatables.bootstrap.js' }}" type="text/javascript"></script>
<script src="{{ $CDN_PATH.'resources/pages/scripts/packages/contactinfo/table-contacts-ajax.js?v='.time() }}" type="text/javascript"></script>
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
    var moduleName = 'contactinfo';</script>
@endif

@endsection