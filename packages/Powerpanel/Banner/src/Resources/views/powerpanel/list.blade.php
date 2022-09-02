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
            @if($total > 0)
                <div class="card-header cmpage-topheader border border-dashed border-end-0 border-start-0 border-top-0">
                    <div class="d-xl-flex flex-wrap align-items-center">

                        <div class="flex-shrink-0 addpage-btn order-sm-2">
                            @can('banners-create')
                                <a class="btn btn-light btn-theme bg-gradient waves-effect waves-light btn-label" href="{{ url('powerpanel/banners/add') }}">
                                    <div class="d-flex">
                                        <div class="flex-shrink-0">
                                            <i class="ri-add-line label-icon align-middle fs-20 me-2"></i>
                                        </div>
                                        <div class="flex-grow-1">
                                            {{ trans('banner::template.bannerModule.add') }}
                                        </div>
                                    </div>
                                </a>
                            @endcan
                        </div>

                        <div class="cm-filter flex-grow-1 order-sm-1 d-flex align-items-center">
                            @if (File::exists(base_path() . '/resources/views/powerpanel/partials/tabpanel.blade.php') != null)
                                @include('powerpanel.partials.tabpanel',['tabarray'=>['favoriteTotalRecords','draftTotalRecords','trashTotalRecords','approvalTotalRecords']])
                            @endif
                            <div class="btn-group d-inline-block filter-dropdown">
                                <button type="button" class="btn fs-14 fw-medium p-0 border-0 filter-btn" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><i class="ri-filter-line fs-21"></i></button>
                                <div class="dropdown-menu">
                                    <div class="p-3 dropdown-body">
                                        <div class="mb-3" id="hidefilter">
                                            <select class="form-select" id="statusfilter">
                                                <option value="">{!! trans('banner::template.common.selectstatus') !!}</option>
                                                <option value="Y">{!! trans('banner::template.common.publish') !!}</option>
                                                <option value="N">{!! trans('banner::template.common.unpublish') !!}</option>
                                            </select>
                                        </div>
                                        <div class="mb-3" id="hidefilter">
                                            <select name="rolefilter" class="form-select" id="bannerFilterType">
                                                <option value="">{!! trans('banner::template.bannerModule.bannerType') !!}</option>
                                                <option value="home_banner"> {!! trans('banner::template.bannerModule.homeBanner') !!}</option>
                                                <option value="inner_banner">{!! trans('banner::template.bannerModule.innerBanner') !!}</option>
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
                                            ['Identity_Name'=>'image','TabIndex'=>'2','Name'=>'Image'],
                                            ['Identity_Name'=>'bannertype','TabIndex'=>'3','Name'=>'Banner Type'],
                                            ['Identity_Name'=>'page','TabIndex'=>'4','Name'=>'Page'],
                                            ['Identity_Name'=>'sdate','TabIndex'=>'5','Name'=>'Start Date'],
                                            ['Identity_Name'=>'order','TabIndex'=>'6','Name'=>'Order'],
                                            ['Identity_Name'=>'publish','TabIndex'=>'7','Name'=>'Publish'],
                                            ['Identity_Name'=>'dactions','TabIndex'=>'8','Name'=>'Action']
                                        ],
                                        'DataTableHead'=>[
                                            ['Title'=>'Title','Align'=>'left'],
                                            ['Title'=>'Image','Align'=>'left'],
                                            ['Title'=>'Banner Type','Align'=>'left'],
                                            ['Title'=>'Page','Align'=>'left'],
                                            ['Title'=>'Start Date','Align'=>'left'],
                                            ['Title'=>'Order','Align'=>'left'],
                                            ['Title'=>'Publish','Align'=>'left'],
                                            ['Title'=>'Action','Align'=>'right']
                                        ]
                                    ]
                                ];
                            @endphp
                            @include('powerpanel.partials.datatable-view',['ModuleName'=>'Banners','Permission_Delete'=>'banners-delete','tablearray'=>$tablearray,'userIsAdmin'=>$userIsAdmin,'Module_ID'=>Config::get('Constant.MODULE.ID')])
                        </div>
                    </div>
                </div><!-- end card-body -->

                <!-- Modal -->
                @if (File::exists(base_path() . '/resources/views/powerpanel/partials/quickeditpopup.blade.php') != null)
                @include('powerpanel.partials.quickeditpopup',['TableName'=>'banners'])
                @endif
                @else
                @if (File::exists(base_path() . '/resources/views/powerpanel/partials/addrecordsection.blade.php') != null)
                @include('powerpanel.partials.addrecordsection',['type'=>Config::get('Constant.MODULE.TITLE'), 'adUrl' => url('powerpanel/banners/add')])
                @endif
            @endif

        </div><!-- end card -->
    </div>
    <!-- end col -->
</div>


{{-- <div class="modal fade bs-example-modal-md" tabindex="-1" role="dialog" id="confirm_share" aria-labelledby="myLargeModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-md">
        <div class="modal-content">
            <div class="modal-header">
				<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
			</div>
            <form role="form" id='frmshareoption'>
                <div class="modal-body delMsg text-center">
                    <div class="form-body">
                        <div class="mb-3">
                            <input name="varTitle" class="form-control spinner" placeholder="{!! trans('banner::template.bannerModule.processSomething') !!}" type="text">
                        </div>
                        <div class="mb-3">
                            <textarea name="txtDescription" class="form-control" placeholder="{!! trans('banner::template.common.shortdescription') !!}" rows="3"></textarea>
                        </div>
                        <div class="mb-3">
                            <div class="checkbox-list">
                                <label class="checkbox-inline">
                                    <input class="form-check-input" name="socialmedia[]" type="checkbox" value="facebook">
                                    <i class="ri-facebook-fill"></i>&nbsp; {!! trans('banner::template.bannerModule.facebook') !!}
                                </label>
                                <label class="checkbox-inline">
                                    <input class="form-check-input" name="socialmedia[]" type="checkbox" value="twitter">
                                    <i class="ri-twitter-fill"></i>&nbsp; {!! trans('banner::template.bannerModule.twitter') !!}
                                </label>
                                <label class="checkbox-inline">
                                    <input class="form-check-input" name="socialmedia[]" type="checkbox" value="linkedin">
                                    <i class="ri-linkedin-fill"></i>&nbsp; {!! trans('banner::template.bannerModule.linkedin') !!}
                                </label>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary">{!! trans('banner::template.common.submit') !!}</button>
                </div>
            </form>
        </div>
    </div>
</div> --}}


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

@php
    $referer = request()->headers->get('referer');
    $pos = strpos($referer, $currenturl);
@endphp

@section('scripts')
<script type="text/javascript">
    window.site_url = '{!! url("/") !!}';
    var DELETE_URL = '{!! url("/powerpanel/banners/DeleteRecord") !!}';
    var onePushShare = '{!! url("/powerpanel/banners/share") !!}';
    var APPROVE_URL = '{!! url("/powerpanel/banners/ApprovedData_Listing") !!}';
    var getChildData = window.site_url + "/powerpanel/banners/getChildData";
    var getChildData_rollback = window.site_url + "/powerpanel/banners/getChildData_rollback";
    var ApprovedData_Listing = window.site_url + "/powerpanel/banners/ApprovedData_Listing";
    var rollbackRoute = window.site_url + "/powerpanel/banners/rollback-record";
    var Get_Comments = window.site_url + "/powerpanel/banners/Get_Comments";
    var Quick_module_id = '@php echo Config::get("Constant.MODULE.ID"); @endphp';
    var settingarray = jQuery.parseJSON('{!!$settingarray!!}');
    var showChecker = true;

    @if (!$userIsAdmin)
        showChecker = false;
    @endif

    @if ($pos == false)
        clearcookie = 'true';
    @else
        clearcookie = 'false';
    @endif
</script>

<script src="{{ $CDN_PATH.'resources/global/plugins/jquery-cookie-master/src/jquery.cookie.js' }}" type="text/javascript"></script>
<script src="{{ $CDN_PATH.'resources/global/scripts/datatable.js' }}" type="text/javascript"></script>
<script src="{{ $CDN_PATH.'resources/global/plugins/datatables/datatables.min.js' }}" type="text/javascript"></script>
<script src="{{ $CDN_PATH.'resources/global/plugins/datatables/plugins/bootstrap/datatables.bootstrap.js' }}" type="text/javascript"></script>
<script src="{{ $CDN_PATH.'resources/pages/scripts/packages/banner/table-banners-ajax.js?v='.time() }}" type="text/javascript"></script>
@if((File::exists(app_path() . '/Workflow.php') != null || File::exists(base_path() . '/packages/Powerpanel/Workflow/src/Models/Workflow.php') != null))
    <script src="{{ $CDN_PATH.'resources/pages/scripts/user-updates-approval.js' }}" type="text/javascript"></script>
@endif
<script src="{{ $CDN_PATH.'resources/pages/scripts/packages/banner/banners-index-validations.js?v='.time() }}" type="text/javascript"></script>
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
    var moduleName = 'banners';</script>
@endif

@endsection