@extends('powerpanel.layouts.app')
@section('title')
{{Config::get('Constant.SITE_NAME')}} - PowerPanel
@stop
@section('css')
<!-- <link href="{{ $CDN_PATH.'resources/global/plugins/datatables/datatables.min.css' }}" rel="stylesheet" type="text/css" />
<link href="{{ $CDN_PATH.'resources/global/plugins/datatables/plugins/bootstrap/datatables.bootstrap.css' }}" rel="stylesheet" type="text/css" />
<link href="{{ $CDN_PATH.'resources/global/plugins/fancybox/source/helpers/jquery.fancybox-thumbs.css' }}" rel="stylesheet" type="text/css"/> -->
<link href="{{ $CDN_PATH.'resources/global/plugins/highslide/highslide.css' }}" rel="stylesheet" type="text/css"/>
<!-- <link href="{{ $CDN_PATH.'resources/global/plugins/tooltips/tooltip.css' }}" rel="stylesheet" type="text/css"/> -->
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
                        @can('number-allocation-create')
                            @if( isset(App\Helpers\MyLibrary::getFront_Uri('number-allocation')['uri']) )
                                <a class="btn btn-light bg-gradient waves-effect waves-light btn-label" href="{{ url('powerpanel/number-allocation/add') }}">
                                    <div class="d-flex">
                                        <div class="flex-shrink-0">
                                            <i class="ri-add-line label-icon align-middle fs-20 me-2"></i>
                                        </div>
                                        <div class="flex-grow-1">
                                            {{ trans('number-allocation::template.numberAllocationModule.add') }}
                                        </div>
                                    </div>
                                </a>
                            @endif
                        @endcan
                    </div>
                </div>
            </div><!-- end card header -->

            <div class="card-body border border-dashed border-end-0 border-start-0">
                <div class="row g-3">
                    <div class="col-lg-2 col-sm-4">
                        <div class="search-box">
                            <input type="search" class="form-control search" placeholder="Search by NXX#" id="searchfilter">
                            <i class="ri-search-line search-icon"></i>
                        </div>
                    </div>
                    <div class="col-lg-2 col-sm-4" id="hidefilter">
                        <select class="form-control" id="statusfilter" data-choices data-choices-search-false>
                            <option value="">{!! trans('number-allocation::template.common.selectstatus') !!}</option>
                            <option value="Y">{!! trans('number-allocation::template.common.publish') !!}</option>
                            <option value="N">{!! trans('number-allocation::template.common.unpublish') !!}</option>
                        </select>
                    </div>

                    @if(!empty($userIsAdmin))
                    <div class="col-lg-2 col-sm-4">
                        <select id="sectorfilter" class="form-control" data-choices data-choices-search-false>
                            <option value="">{{ trans('Select Sector') }}</option>
                            @if(!empty($sectorList))
                                @foreach($sectorList as $key =>  $ValueSector)
                                    <option value="{{$key}}">{{$ValueSector}}</option>
                                @endforeach
                            @endif
                        </select>
                    </div>
                    @endif

                    <div class="col-lg-4 col-sm-6" id="hidefilter">
                        <select class="form-control category_filter" name="category" id="category" data-choices data-choices-search-false>
                            <option value="">Select Company Category</option>
                            @foreach ($numberAllocationCategory as $cat)
                                @php $permissionName = 'number-allocation-list' @endphp
                                @php $selected = ''; @endphp
                                @if(isset($blog->intFKCategory))
                                    @if($cat['id'] == $blog->intFKCategory)
                                        @php $selected = 'selected'; @endphp
                                    @endif
                                @endif
                            <option value="{{ $cat['id'] }}" {{ $selected }} >{{ $cat['varModuleName']== "managementteam"?'Select Company Category':$cat['varTitle'] }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-lg-1 col-sm-2">
                        <button type="button" class="btn btn-light waves-effect waves-light btn-light btn-label" title="Reset" id="refresh">
                            <div class="d-flex">
                                <div class="flex-shrink-0">
                                    <i class="ri-refresh-line label-icon align-middle fs-20 me-2"></i>
                                </div>
                                <div class="flex-grow-1">
                                    Reset
                                </div>
                            </div>
                        </button>
                    </div>
                </div><!--end row-->
            </div><!-- end card body -->

            @if($iTotalRecords > 0)
                <div class="card-body">
                    <div class="live-preview">
                        @if(Session::has('message'))
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            {{ Session::get('message') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                        @endif

                        @if (File::exists(base_path() . '/resources/views/powerpanel/partials/tabpanel.blade.php') != null)
                            @include('powerpanel.partials.tabpanel',['tabarray'=>['favoriteTotalRecords','draftTotalRecords','trashTotalRecords']])
                        @endif
                            @php 
                            $tablearray = [
                                'DataTableTab'=>[
                                    'ColumnSetting'=>[
                                        ['Identity_Name'=>'nxx','TabIndex'=>'2','Name'=>'NXX#'],
                                        ['Identity_Name'=>'cat','TabIndex'=>'3','Name'=>'Company Category'],
                                        ['Identity_Name'=>'service','TabIndex'=>'4','Name'=>'Service'],
                                        ['Identity_Name'=>'order','TabIndex'=>'5','Name'=>'Display Order'],
                                        ['Identity_Name'=>'publish','TabIndex'=>'6','Name'=>'Publish'],
                                        ['Identity_Name'=>'log','TabIndex'=>'7','Name'=>'Action']
                                    ],
                                    'DataTableHead'=>[
                                        ['Title'=>'NXX#','Align'=>'left'],
                                        ['Title'=>'Company Category','Align'=>'left'],
                                        ['Title'=>'Services','Align'=>'left'],
                                        ['Title'=>'Display Order','Align'=>'left'],
                                        ['Title'=>'Publish','Align'=>'left'],
                                        ['Title'=>'Action','Align'=>'right']
                                    ]
                                ]
                            ];
                            @endphp
                            @include('powerpanel.partials.datatable-view',['ModuleName'=>'Mange Number Allocations','Permission_Delete'=>'number-allocation-delete','tablearray'=>$tablearray,'userIsAdmin'=>$userIsAdmin,'Module_ID'=>Config::get('Constant.MODULE.ID')])
                    </div>
                </div><!-- end card-body -->
                <!-- Modal -->
                @if (File::exists(base_path() . '/resources/views/powerpanel/partials/quickeditpopup.blade.php') != null)
                @include('powerpanel.partials.quickeditpopup',['TableName'=>'number_allocation'])
                @endif
                @else
                @if (File::exists(base_path() . '/resources/views/powerpanel/partials/addrecordsection.blade.php') != null)
                @include('powerpanel.partials.addrecordsection',['type'=>Config::get('Constant.MODULE.TITLE'), 'adUrl' => url('powerpanel/number-allocation/add')])
                @endif
            @endif
        </div><!-- end card -->
    </div>
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
    var DELETE_URL = '{!! url("powerpanel/number-allocation/DeleteRecord") !!}';
    var APPROVE_URL = '{!! url("/powerpanel/number-allocation/ApprovedData_Listing") !!}';
    var getChildData = window.site_url + "/powerpanel/number-allocation/getChildData";
    var getChildData_rollback = window.site_url + "/powerpanel/number-allocation/getChildData_rollback";
    var ApprovedData_Listing = window.site_url + "/powerpanel/number-allocation/ApprovedData_Listing";
    var rollbackRoute = window.site_url + "/powerpanel/number-allocation/rollback-record";
    var Get_Comments = '{!! url("/powerpanel/number-allocation/Get_Comments") !!}';
    var Quick_module_id = '<?php echo Config::get('Constant.MODULE.ID'); ?>';
     var settingarray = jQuery.parseJSON('{!!$settingarray!!}');
    var showChecker = true;
    @if (!$userIsAdmin)
    showChecker = false;
    @endif
    var onePushShare = '{!! url("/powerpanel/share") !!}';
    var onePushGetRec = '{!! url("/powerpanel/share/getrec") !!}';
</script>
<script src="{{ $CDN_PATH.'resources/global/plugins/jquery-cookie-master/src/jquery.cookie.js' }}" type="text/javascript"></script>
<script src="{{ $CDN_PATH.'resources/global/scripts/datatable.js' }}" type="text/javascript"></script>
<script src="{{ $CDN_PATH.'resources/global/plugins/datatables/datatables.min.js' }}" type="text/javascript"></script>
<script src="{{ $CDN_PATH.'resources/global/plugins/datatables/dataTables.editor.js' }}" type="text/javascript"></script>
<script src="{{ $CDN_PATH.'resources/global/plugins/datatables/plugins/bootstrap/datatables.bootstrap.js' }}" type="text/javascript"></script>
<!-- <script src="{{ $CDN_PATH.'resources/global/plugins/fancybox/source/helpers/jquery.fancybox-thumbs.js' }}" type="text/javascript"></script> -->
<script src="{{ $CDN_PATH.'resources/pages/scripts/packages/number-allocation/number-allocation-datatables-ajax.js' }}" type="text/javascript"></script>
<script src="{{ $CDN_PATH.'resources/pages/scripts/custom.js' }}" type="text/javascript"></script>
<script src="{{ $CDN_PATH.'resources/global/plugins/highslide/highslide-with-html.js' }}" type="text/javascript"></script>
 @if((File::exists(app_path() . '/Workflow.php') != null || File::exists(base_path() . '/packages/Powerpanel/Workflow/src/Models/Workflow.php') != null))
<script src="{{ $CDN_PATH.'resources/pages/scripts/user-updates-approval.js' }}" type="text/javascript"></script>
@endif
<script src="{{ $CDN_PATH.'resources/pages/scripts/sharer-validations.js' }}" type="text/javascript"></script>
<script type="text/javascript">
    // $('.fancybox-buttons').fancybox({
    //     autoWidth: true,
    //     autoHeight: true,
    //     autoResize: true,
    //     autoCenter: true,
    //     closeBtn: true,
    //     openEffect: 'elastic',
    //     closeEffect: 'elastic',
    //     helpers: {
    //         title: {
    //             type: 'inside',
    //             position: 'top'
    //         }
    //     },
    //     beforeShow: function () {
    //         this.title = $(this.element).data("title");
    //     }
    // });
    // $(".fancybox-thumb").fancybox({
        
    //     prevEffect: 'none',
    //     nextEffect: 'none',
    //     helpers:
    //             {
    //                 title: {
    //                     type: 'outside'
    //                 },
    //                 thumbs: {
    //                     width: 60,
    //                     height: 50
    //                 }
    //             }
    // });
    $(document).ready(function () {
        setInterval(function () {
            $('.addhiglight').closest("td").closest("tr").addClass('higlight');
        }, 800);
    });
</script>
@endsection