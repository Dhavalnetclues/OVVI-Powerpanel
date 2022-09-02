@extends('powerpanel.layouts.app')
@section('title')
{{Config::get('Constant.SITE_NAME')}} - PowerPanel
@stop
@section('css')
<!-- <link href="{{ $CDN_PATH.'resources/global/plugins/datatables/datatables.min.css' }}" rel="stylesheet" type="text/css" />
<link href="{{ $CDN_PATH.'resources/global/plugins/datatables/plugins/bootstrap/datatables.bootstrap.css' }}" rel="stylesheet" type="text/css" /> -->
<link href="{{ $CDN_PATH.'resources/global/plugins/highslide/highslide.css' }}" rel="stylesheet" type="text/css"/>
@endsection
@section('content')
<!-- BEGIN PAGE BASE CONTENT -->
{!! csrf_field() !!}
<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-header border-0">
                <div class="d-flex align-items-center">
                    <h5 class="card-title mb-0 flex-grow-1"></h5>
                    <div class="flex-shrink-0">
                        @can('links-category-create')
                            <a class="btn btn-light bg-gradient waves-effect waves-light btn-label" href="{{ url('powerpanel/links-category/add') }}">
                                <div class="d-flex">
                                    <div class="flex-shrink-0">
                                        <i class="ri-add-line label-icon align-middle fs-20  me-2"></i>
                                    </div>
                                    <div class="flex-grow-1">
                                        {{ trans('links-category::template.links-categoryModule.addLinksCategory') }}
                                    </div>
                                </div>
                            </a>
                        @endcan
                    </div>
                </div>
            </div><!-- end card header -->

            <div class="card-body border border-dashed border-end-0 border-start-0">
                <div class="row g-3">
                    <div class="col-xxl-4 col-sm-4">
                        <div class="search-box">
                            <input type="search" class="form-control search" placeholder="Search by Title" id="searchfilter">
                            <i class="ri-search-line search-icon"></i>
                        </div>
                    </div>
                    <div class="col-xxl-2 col-sm-4" id="hidefilter">
                        <select class="form-control" id="statusfilter" data-choices data-choices-search-false>
                            <option value="">{!! trans('links-category::template.common.selectstatus') !!}</option>
                            <option value="Y">{!! trans('links-category::template.common.publish') !!}</option>
                            <option value="N">{!! trans('links-category::template.common.unpublish') !!}</option>
                        </select>
                    </div>
                    @if(!empty($userIsAdmin))
                        <span class="col-xxl-2 col-sm-4">
                        <select id="sectorfilter" class="form-control" data-choices>
                            <option value="">{{ trans('Select Sector') }}</option>
                            @if(!empty($sectorList))
                                @foreach($sectorList as $key =>  $ValueSector)
                                    <option value="{{$key}}">{{$ValueSector}}</option>
                                @endforeach
                            @endif
                        </select>
                    </span>
                    @endif
                    <!--end col-->
                    <div class="col-xxl-1 col-sm-2">
                        <button type="button" class="btn btn-soft-secondary waves-effect waves-light btn-light btn-sm" title="Reset" id="refresh">
                            <i class="ri-refresh-line"></i>
                        </button>
                    </div>
                    <!--end col-->
                </div><!--end row-->
            </div><!-- end card body -->

            @if(Session::has('message'))
            <div class="alert alert-success">
                {{ Session::get('message') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
            @endif

            @if($iTotalRecords > 0)
                <div class="card-body">
                    <div class="live-preview">
                        @if (File::exists(base_path() . '/resources/views/powerpanel/partials/tabpanel.blade.php') != null)
                        @include('powerpanel.partials.tabpanel',['tabarray'=>['favoriteTotalRecords','draftTotalRecords','trashTotalRecords']])
                        @endif
                        @php
                        $tablearray = [
                            'DataTableTab'=>[
                                'ColumnSetting'=>[
                                    ['Identity_Name'=>'title','TabIndex'=>'2','Name'=>'Title'],
                                    ['Identity_Name'=>'order','TabIndex'=>'3','Name'=>'Order'],
                                    ['Identity_Name'=>'publish','TabIndex'=>'4','Name'=>'Publish'],
                                    ['Identity_Name'=>'dactions','TabIndex'=>'5','Name'=>'Action']
                                ],
                                'DataTableHead'=>[
                                    ['Title'=>'Title','Align'=>'left'],
                                    ['Title'=>'Order','Align'=>'center'],
                                    ['Title'=>'Publish','Align'=>'center'],
                                    ['Title'=>'Action','Align'=>'right']
                                ]
                            ]
                        ];
                        @endphp
                        @include('powerpanel.partials.datatable-view',['ModuleName'=>'LinksCategory','Permission_Delete'=>'links-category-delete','tablearray'=>$tablearray,'userIsAdmin'=>$userIsAdmin,'Module_ID'=>Config::get('Constant.MODULE.ID')])
                    </div>
                </div><!-- end card-body -->
                <!-- Modal -->
                @if (File::exists(base_path() . '/resources/views/powerpanel/partials/quickeditpopup.blade.php') != null)
                @include('powerpanel.partials.quickeditpopup',['TableName'=>'linkscategory'])
                @endif
                @else
                @if (File::exists(base_path() . '/resources/views/powerpanel/partials/addrecordsection.blade.php') != null)
                @include('powerpanel.partials.addrecordsection',['type'=>Config::get('Constant.MODULE.TITLE'), 'adUrl' => url('powerpanel/links-category/add')])
                @endif
            @endif
        </div><!-- end card -->
    </div>
</div>
@if (File::exists(base_path() . '/resources/views/powerpanel/partials/deletePopup.blade.php') != null)
@include('powerpanel.partials.deletePopup')
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
    var DELETE_URL = '{!! url("/powerpanel/links-category/DeleteRecord") !!}';
    var APPROVE_URL = '{!! url("/powerpanel/links-category/ApprovedData_Listing") !!}';
    var rollbackRoute = window.site_url + "/powerpanel/links-category/rollback-record";
    var getChildData = window.site_url + "/powerpanel/links-category/getChildData";
    var getChildData_rollback = window.site_url + "/powerpanel/links-category/getChildData_rollback";
    var ApprovedData_Listing = window.site_url + "/powerpanel/links-category/ApprovedData_Listing";
    var Get_Comments = '{!! url("/powerpanel/links-category/Get_Comments") !!}';
    var Quick_module_id = '<?php echo Config::get('Constant.MODULE.ID'); ?>';
    var settingarray = jQuery.parseJSON('{!!$settingarray!!}');
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
<script src="{{ $CDN_PATH.'resources/pages/scripts/packages/linkcategory/links-category-datatables-ajax.js' }}" type="text/javascript"></script>
<script src="{{ $CDN_PATH.'resources/pages/scripts/custom.js' }}" type="text/javascript"></script>
@if((File::exists(app_path() . '/Workflow.php') != null || File::exists(base_path() . '/packages/Powerpanel/Workflow/src/Models/Workflow.php') != null))
<script src="{{ $CDN_PATH.'resources/pages/scripts/user-updates-approval.js' }}" type="text/javascript"></script>
@endif
<script type="text/javascript">
    $(document).ready(function () {
        setInterval(function () {
            $('.addhiglight').closest("td").closest("tr").addClass('higlight');
        }, 800);
    });
</script>
@endsection