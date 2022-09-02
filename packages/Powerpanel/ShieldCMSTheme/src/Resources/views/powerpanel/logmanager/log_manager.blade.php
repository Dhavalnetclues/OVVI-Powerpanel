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
                        <div class="cm-filter flex-grow-1 order-sm-1 d-flex align-items-center">
                            @if (File::exists(base_path() . '/resources/views/powerpanel/partials/tabpanel.blade.php') != null)
                                {{-- @include('powerpanel.partials.tabpanel',['tabarray'=>[]]) --}}
                            @endif
                            <div class="btn-group d-inline-block filter-dropdown">
                                <button type="button" class="btn fs-14 fw-medium p-0 border-0 filter-btn" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><i class="ri-filter-line fs-21"></i></button>
                                <div class="dropdown-menu">
                                    <div class="p-3 dropdown-body">

                                        @if (!isset($_REQUEST['id']) && !isset($_REQUEST['mid']))
                                            <div class="mb-3">
                                                <select id="modulefilter" name="modulefilter" class="form-select">
                                                    <option value="">{!!  trans('shiledcmstheme::template.common.selectmodule') !!}</option>
                                                    @if(count($modules) > 0)
                                                        @foreach ($modules as $pagedata)
                                                            @php
                                                                $avoidModules = array('email-log','role','search-statictics','hits-report','liveuser','workflow','users','blocked_ips','testimonial','blogs','blog-category','news-category','service-category','privacy-removal-leads','menu','menu-type','log','login-history');
                                                            @endphp
                                                            @if (ucfirst($pagedata->varTitle)!='Home' && !in_array($pagedata->varModuleName,$avoidModules) && Auth::user()->can($pagedata['varModuleName'] . '-list'))
                                                                <option data-model="{{ $pagedata->varModelName }}" data-module="{{ $pagedata->varModuleName }}" data-namespace="{{ $pagedata->varModuleNameSpace }}" data-id="{{ $pagedata->id }}" value="{{ $pagedata->id }}" {{ (isset($banners->fkModuleId) && $pagedata->id == $banners->fkModuleId) || $pagedata->id == old('modules')? 'selected' : '' }} >{{ $pagedata->varTitle }}</option>
                                                            @endif
                                                        @endforeach
                                                    @endif
                                                </select>
                                            </div>

                                            {{-- <div class="mb-3">
                                                <select id="foritem" name="foritem"  class="form-select">
                                                    <option value="">{!!  trans('shiledcmstheme::template.bannerModule.selectPage') !!}</option>
                                                </select>
                                            </div> --}}

                                            <div class="mb-3" style="display: none">
                                                <select id="userfilter" name="userfilter"  class="form-select">
                                                    <option value="">{!!  trans('shiledcmstheme::template.common.selectuser') !!}</option>
                                                    @if(!empty($userslist))
                                                        @foreach ($userslist as $users)
                                                            @php
                                                                $avoidUsers = array();
                                                            @endphp
                                                            @if (!in_array($users->id,$avoidUsers))
                                                                <option value="{{ $users->id }}" {{ $users->id == old('userfilter')? 'selected' : '' }} >{{ $users->name }}</option>
                                                            @endif
                                                        @endforeach
                                                    @endif
                                                </select>
                                            </div>
                                        @endif

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
                                <input type="search" class="form-control search" placeholder="Search by User" id="searchfilter">
                                <span class="iconsearch cursor-pointer"><i class="ri-search-2-line fs-21"></i></span>
                            </div>
                        </div>
                        <div class="flex-shrink-0 order-sm-2 mt-2 mt-lg-0">
                            <div class="portlet-rh-title">
                                <div class="public-status pub-log-status">
                                    <ul class="m-0 p-0">
                                        <li class="pub_status adddiv"><a class="list_head_filter" href="javascript:void(0);" data-filterIdentity="add"><span>Add</span></a></li>
                                        <li class="pub_status updatediv"><a class="list_head_filter" href="javascript:void(0);" data-filterIdentity="edit"><span>Update</span></a></li>
                                        <li class="pub_status deletediv"><a class="list_head_filter" href="javascript:void(0);" data-filterIdentity="delete"><span>Delete</span></a></li>
                                        <li class="pub_status transhdiv"><a class="list_head_filter" href="javascript:void(0);" data-filterIdentity="trash"><span>Trash</span></a></li>
                                        <li class="pub_status commentdiv"><a class="list_head_filter" href="javascript:void(0);" data-filterIdentity="comment"><span>Comment</span></a></li>
                                        <li class="pub_status approveddiv"><a class="list_head_filter" href="javascript:void(0);" data-filterIdentity="approved"><span>Approved</span></a></li>
                                        <li class="pub_status copydiv"><a class="list_head_filter" href="javascript:void(0);" data-filterIdentity="copy"><span>Copy</span></a></li>
                                        <li class="pub_status otherdiv"><a class="list_head_filter" href="javascript:void(0);" data-filterIdentity="other"><span>Other</span></a></li>
                                    </ul>
                                </div>
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
                                            ['Identity_Name'=>'','TabIndex'=>'1','Name'=>''],
                                            ['Identity_Name'=>'user','TabIndex'=>'2','Name'=>'User'],
                                            ['Identity_Name'=>'name','TabIndex'=>'3','Name'=>'Name'],
                                            ['Identity_Name'=>'dactions','TabIndex'=>'4','Name'=>'Action'],
                                            ['Identity_Name'=>'ip','TabIndex'=>'5','Name'=>'IP Address'],
                                            ['Identity_Name'=>'date','TabIndex'=>'6','Name'=>'Date & Time'],
                                        ],
                                        'DataTableHead'=>[
                                            ['Title'=>'','Align'=>'left'],
                                            ['Title'=>'User','Align'=>'left'],
                                            ['Title'=>'Name','Align'=>'left'],
                                            ['Title'=>'Action','Align'=>'right'],
                                            ['Title'=>'IP Address','Align'=>'left'],
                                            ['Title'=>'Date & Time','Align'=>'left'],
                                        ]
                                    ]
                                ];
                            @endphp
                            @include('powerpanel.partials.datatable-view',['ModuleName'=>'LogManager','Permission_Delete'=>'log-delete','tablearray'=>$tablearray,'userIsAdmin'=>$userIsAdmin,'Module_ID'=>Config::get('Constant.MODULE.ID')])
                        </div>
                    </div>
                </div><!-- end card-body -->

                <!-- Modal -->
                @if (File::exists(base_path() . '/resources/views/powerpanel/partials/quickeditpopup.blade.php') != null)
                @include('powerpanel.partials.quickeditpopup',['TableName'=>'logs'])
                @endif
                @else
                @if (File::exists(base_path() . '/resources/views/powerpanel/partials/addrecordsection.blade.php') != null)
                @include('powerpanel.partials.addrecordsection',['type'=>Config::get('Constant.MODULE.TITLE'), 'adUrl' => url('powerpanel/log/add')])
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
                <h5 class="modal-title">{{ trans('shiledcmstheme::template.common.alert') }}</h5>
                <button type="button" class="btn-close fs-10" data-bs-dismiss="modal" aria-label="Close"> </button>
            </div>
            <div class="modal-body">
                <h5 class="mb-2">{{ trans('shiledcmstheme::template.common.noExport') }}</h5>
                <div class="pt-2">
                    <button type="button" class="btn btn-primary bg-gradient waves-effect waves-light btn-label" data-bs-dismiss="modal">
                        <div class="d-flex">
                            <div class="flex-shrink-0">
                                <i class="ri-check-line label-icon align-middle fs-20 me-2"></i>
                            </div>
                            <div class="flex-grow-1">
                                {{ trans('shiledcmstheme::template.common.ok') }}
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
                <h5 class="modal-title">{{ trans('shiledcmstheme::template.common.alert') }}</h5>
                <button type="button" class="btn-close fs-10" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-sm-12">
                        <h5 class="mb-2">{{ trans('shiledcmstheme::template.common.recordsExport') }}</h5>
                        <div class="form-check form-check-inline">
                            <input class="form-check-input" type="radio" value="selected_records" id="selected_records" name="export_type">
                            <label for="selected_records">{{ trans('shiledcmstheme::template.common.selectedRecords') }}</label>
                        </div>
                        <div class="form-check form-check-inline">
                            <input class="form-check-input" type="radio" value="all_records" id="all_records" name="export_type" checked>
                            <label for="all_records">{{ trans('shiledcmstheme::template.common.allRecords') }}</label>
                        </div>
                    </div>
                    <div class="col-sm-12 mt-3">
                        <button type="button" class="btn btn-primary bg-gradient waves-effect waves-light btn-label" id="ExportRecord" data-bs-dismiss="modal">
                            <div class="d-flex">
                                <div class="flex-shrink-0">
                                    <i class="ri-check-line label-icon align-middle fs-20 me-2"></i>
                                </div>
                                <div class="flex-grow-1">
                                    {{ trans('shiledcmstheme::template.common.ok') }}
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
                <h5 class="modal-title">{{ trans('shiledcmstheme::template.common.alert') }}</h5>
                <button type="button" class="btn-close fs-10" data-bs-dismiss="modal" aria-label="Close"> </button>
            </div>
            <div class="modal-body">
                <h5 class="mb-2">{{ trans('shiledcmstheme::template.common.leastRecord') }}</h5>
                <div class="pt-2">
                    <button type="button" class="btn btn-primary bg-gradient waves-effect waves-light btn-label" data-bs-dismiss="modal">
                        <div class="d-flex">
                            <div class="flex-shrink-0">
                                <i class="ri-check-line label-icon align-middle fs-20 me-2"></i>
                            </div>
                            <div class="flex-grow-1">
                                {{ trans('shiledcmstheme::template.common.ok') }}
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
    var DELETE_URL = '{!! url("/powerpanel/log/DeleteRecord") !!}';
    var QuerySringParams = {};

    $(document).ready(function () {
        var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
        var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
        return new bootstrap.Tooltip(tooltipTriggerEl)
        })
    });
</script>

@php if (isset($_REQUEST['id']) && $_REQUEST['id'] != '') { @endphp
    <script type="text/javascript">
        var rid = '?rid=@php echo $_REQUEST['id'] @endphp';
        QuerySringParams.rid = '@php echo $_REQUEST['id'] @endphp';
    </script>
@php } else { @endphp
    <script type="text/javascript">
        var rid = '';
    </script>
@php } @endphp
@php if (isset($_REQUEST['mid']) && $_REQUEST['mid'] != '') { @endphp
    <script type="text/javascript">
        var mid = '&mid=@php echo $_REQUEST['mid'] @endphp';
        QuerySringParams.mid = '@php echo $_REQUEST['mid'] @endphp';
    </script>
@php } else { @endphp
    <script type="text/javascript">
        var mid = '';
    </script>
@php } @endphp
<script type="text/javascript">
    var showChecker = true;
            @if (!$userIsAdmin)
    showChecker = false;
    @endif
    var selectedRecord = '';
</script>

<script src="{{ $CDN_PATH.'resources/global/plugins/jquery-cookie-master/src/jquery.cookie.js' }}" type="text/javascript"></script>
<script src="{{ $CDN_PATH.'resources/global/scripts/datatable.js' }}" type="text/javascript"></script>
<script src="{{ $CDN_PATH.'resources/global/plugins/datatables/datatables.min.js' }}" type="text/javascript"></script>
<script src="{{ $CDN_PATH.'resources/global/plugins/datatables/plugins/bootstrap/datatables.bootstrap.js' }}" type="text/javascript"></script>
<script src="{{ $CDN_PATH.'resources/pages/scripts/packages/logmanager/log-datatables-ajax.js?v='.time() }}" type="text/javascript"></script>
<script src="{{ $CDN_PATH.'resources/global/plugins/highslide/highslide-with-html-log.js?v='.time() }}" type="text/javascript"></script>
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
    var moduleName = 'logs';</script>
@endif

@endsection