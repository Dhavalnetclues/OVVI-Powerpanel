@extends('powerpanel.layouts.app')
@section('title')
{{Config::get('Constant.SITE_NAME')}} - PowerPanel
@stop
@section('css')
<link href="{{ $CDN_PATH.'resources/global/plugins/datatables/datatables.min.css' }}" rel="stylesheet" type="text/css" />
<link href="{{ $CDN_PATH.'resources/global/plugins/datatables/plugins/bootstrap/datatables.bootstrap.css' }}" rel="stylesheet" type="text/css" />
<link href="{{ $CDN_PATH.'resources/global/plugins/fancybox/source/helpers/jquery.fancybox-thumbs.css' }}" rel="stylesheet" type="text/css"/>
<link href="{{ $CDN_PATH.'resources/global/plugins/highslide/highslide.css' }}" rel="stylesheet" type="text/css"/>
<link href="{{ $CDN_PATH.'resources/global/plugins/tooltips/tooltip.css' }}" rel="stylesheet" type="text/css"/>
@endsection
@section('content')
<!-- BEGIN PAGE BASE CONTENT -->
{!! csrf_field() !!}
<div class="row">
    <div class="col-md-12">
        <!-- TITILE HEAD START -->
        <div class="title-dropdown_sec">
            @if (File::exists(base_path() . '/resources/views/powerpanel/partials/listbreadcrumbs.blade.php') != null)
                @include('powerpanel.partials.listbreadcrumbs',['ModuleName'=>'Online Polling'])
            @endif

            @if($iTotalRecords > 0)
                <div id="cmspage_id" class="collapse in">
                    <div class="collapse-inner">
                        <div class="portlet-title select_box filter-group">
                            <div class="row">
                                <div class="col-lg-6 col-md-12 col-xs-12">
                                    <div class="portlet-lf-title">
                                        <div class="sub_select_filter" id="hidefilter">
                                            <span class="title">{!! trans('polls::template.common.filterby') !!}:</span>
                                            <span class="select_input">
                                                <select id="statusfilter" data-sort data-order class="bs-select select2">
                                                    <option value=" ">{!! trans('polls::template.common.selectstatus') !!}</option>
                                                    <option value="Y">{!! trans('polls::template.common.publish') !!}</option>
                                                    <option value="N">{!! trans('polls::template.common.unpublish') !!}</option>
                                                </select>
                                            </span>   
                                            <span class="btn btn-icon-only btn-green-drake green-new" type="button" id="refresh" title="Reset">
                                                <i class="fa fa-refresh" aria-hidden="true"></i>
                                            </span>
                                        </div>
                                    </div>    
                                </div>
                                <div class="col-lg-6 col-md-12 col-xs-12">
                                    <div class="portlet-rh-title">
                                        @can('online-polling-create')
                                        @if( isset(App\Helpers\MyLibrary::getFront_Uri('online-polling')['uri']) )
                                            <div class="add_category_button pull-right">
                                                <a class="add_category" href="{{ url('powerpanel/online-polling/add') }}"><span>{{ trans('polls::template.onlinepollingModule.addPoll') }}</span> <i class="ri-add-line"></i></a>
                                            </div>
                                        @endif
                                        @endcan
                                    
                                    </div>  
                                </div>    
                            </div>  
                        </div>
                    </div>
                </div> 
            @endif   
        </div>
        <!-- TITILE HEAD End... --> 
        <!-- Begin: life time stats -->
        @if(Session::has('message'))
        <div class="alert alert-success">
            <button class="close" data-close="alert"></button>
            {{ Session::get('message') }}
        </div>
        @endif
        <div class="portlet light portlet-fit portlet-datatable">
            @if( !isset(App\Helpers\MyLibrary::getFront_Uri('online-polling')['uri']) )
            @include('powerpanel.partials.pagenotavailable')
            @elseif($iTotalRecords > 0)

            <div class="portlet-body">
                <div class="table-container">
                    <div class="col-md-12 col-sm-12 ">
                        <div class="row">
                            <div class="row col-md-6 col-sm-12 pull-right search_pages_div">
                                <div class="search_rh_div">
                                    <span>{{ trans('polls::template.common.search') }}:</span>
                                    <input type="search" class="form-control form-control-solid placeholder-no-fix" placeholder="Search by Title" id="searchfilter">
                                </div>
                            </div>
                        </div>
                    </div>
                    @if (File::exists(base_path() . '/resources/views/powerpanel/partials/tabpanel.blade.php') != null)
                    @include('powerpanel.partials.tabpanel',['tabarray'=>['favoriteTotalRecords','draftTotalRecords','trashTotalRecords']])
                    @endif
                         @php
                        $tablearray = [
                            'DataTableTab'=>[
                                'ColumnSetting'=>[
                                    ['Identity_Name'=>'title','TabIndex'=>'1','Name'=>'Title'],
                                    ['Identity_Name'=>'audience_limit','TabIndex'=>'1','Name'=>'Audience Limit'],
                                    ['Identity_Name'=>'sdate','TabIndex'=>'2','Name'=>'Start Date'],
                                    ['Identity_Name'=>'edate','TabIndex'=>'3','Name'=>'End Date'],
                                    ['Identity_Name'=>'hits','TabIndex'=>'4','Name'=> 'Order'],
                                    ['Identity_Name'=>'publish','TabIndex'=>'5','Name'=>'Publish'],
                                    ['Identity_Name'=>'actions','TabIndex'=>'6','Name'=>'Action']
                                ],
                                'DataTableHead'=>[
                                    ['Title'=>'Title','Align'=>'left'],
                                    ['Title'=>'Audience Limit','Align'=>'center'],
                                    ['Title'=>'Start Date','Align'=>'center'],
                                    ['Title'=>'End Date','Align'=>'center'],
                                    ['Title'=>'Order','Align'=>'center'],
                                    ['Title'=>'Publish','Align'=>'center'],
                                    ['Title'=>'Action','Align'=>'right']
                                ]
                            ]
                        ];
                        @endphp
                        @include('powerpanel.partials.datatable-view',['ModuleName'=>'Online Polling','Permission_Delete'=>'online-polling-delete','tablearray'=>$tablearray,'userIsAdmin'=>$userIsAdmin,'Module_ID'=>Config::get('Constant.MODULE.ID')])
                    
                </div>
            </div>
            <!-- Modal -->
             @if (File::exists(base_path() . '/resources/views/powerpanel/partials/quickeditpopup.blade.php') != null)
                @include('powerpanel.partials.quickeditpopup',['TableName'=>'online polling'])
            @endif
            @else
             @if (File::exists(base_path() . '/resources/views/powerpanel/partials/addrecordsection.blade.php') != null)
                @include('powerpanel.partials.addrecordsection',['type'=>Config::get('Constant.MODULE.TITLE'), 'adUrl' => url('powerpanel/online-polling/add')])
            @endif
            @endif
        </div>
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
    var DELETE_URL = '{!! url("powerpanel/online-polling/DeleteRecord") !!}';
    var APPROVE_URL = '{!! url("/powerpanel/online-polling/ApprovedData_Listing") !!}';
    var getChildData = window.site_url + "/powerpanel/online-polling/getChildData";
    var getChildData_rollback = window.site_url + "/powerpanel/online-polling/getChildData_rollback";
    var ApprovedData_Listing = window.site_url + "/powerpanel/online-polling/ApprovedData_Listing";
    var rollbackRoute = window.site_url + "/powerpanel/online-polling/rollback-record";
    var Get_Comments = '{!! url("/powerpanel/online-polling/Get_Comments") !!}';
    var Quick_module_id = '<?php echo Config::get('Constant.MODULE.ID'); ?>';
     var settingarray = jQuery.parseJSON('{!! $settingarray !!}');
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
<script src="{{ $CDN_PATH.'resources/global/plugins/fancybox/source/helpers/jquery.fancybox-thumbs.js' }}" type="text/javascript"></script>
<script src="{{ $CDN_PATH.'resources/pages/scripts/packages/poll/poll-datatables-ajax.js' }}" type="text/javascript"></script>
<script src="{{ $CDN_PATH.'resources/pages/scripts/custom.js' }}" type="text/javascript"></script>
<script src="{{ $CDN_PATH.'resources/global/plugins/highslide/highslide-with-html.js' }}" type="text/javascript"></script>
 @if((File::exists(app_path() . '/Workflow.php') != null || File::exists(base_path() . '/packages/Powerpanel/Workflow/src/Models/Workflow.php') != null))
<script src="{{ $CDN_PATH.'resources/pages/scripts/user-updates-approval.js' }}" type="text/javascript"></script>
@endif
<script src="{{ $CDN_PATH.'resources/pages/scripts/sharer-validations.js' }}" type="text/javascript"></script>
<script type="text/javascript">
    $('.fancybox-buttons').fancybox({
        autoWidth: true,
        autoHeight: true,
        autoResize: true,
        autoCenter: true,
        closeBtn: true,
        openEffect: 'elastic',
        closeEffect: 'elastic',
        helpers: {
            title: {
                type: 'inside',
                position: 'top'
            }
        },
        beforeShow: function () {
            this.title = $(this.element).data("title");
        }
    });
    $(".fancybox-thumb").fancybox({
        
        prevEffect: 'none',
        nextEffect: 'none',
        helpers:
                {
                    title: {
                        type: 'outside'
                    },
                    thumbs: {
                        width: 60,
                        height: 50
                    }
                }
    });
    $(document).ready(function () {
        setInterval(function () {
            $('.addhiglight').closest("td").closest("tr").addClass('higlight');
        }, 800);
    });
</script>
@endsection