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
                                            <select id="emailtypefilter" class="form-select">
                                                <option value="">Select Email Type</option>
                                                @if(isset($emailTypes) && $emailTypes != '')
                                                    @foreach ($emailTypes as $types)
                                                        <option value="{{ $types->id }}">{{ $types->varEmailType }}</option>
                                                    @endforeach
                                                @endif
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
                                <input type="search" class="form-control search" placeholder="Search by EmailType" id="searchfilter">
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
                                        ['Identity_Name'=>'emailType','TabIndex'=>'1','Name'=>'EmailType'],
                                        ['Identity_Name'=>'from','TabIndex'=>'2','Name'=>'From'],
                                        ['Identity_Name'=>'to','TabIndex'=>'3','Name'=>'To'],
                                        ['Identity_Name'=>'isSent','TabIndex'=>'4','Name'=>'Is Sent'],
                                        ['Identity_Name'=>'attachment','TabIndex'=>'5','Name'=>'Attachment'],
                                        ['Identity_Name'=>'dateTime','TabIndex'=>'6','Name'=>'DateTime'],
                                    ],
                                    'DataTableHead'=>[
                                        ['Title'=>'EmailType','Align'=>'left'],
                                        ['Title'=>'From','Align'=>'left'],
                                        ['Title'=>'To','Align'=>'left'],
                                        ['Title'=>'Is Sent','Align'=>'right'],
                                        ['Title'=>'Attachment','Align'=>'right'],
                                        ['Title'=>'DateTime','Align'=>'right'],
                                    ]
                                ]
                            ];
                            @endphp
                            @include('powerpanel.partials.datatable-view',['ModuleName'=>'EmailLog','Permission_Delete'=>'email-log-delete','tablearray'=>$tablearray,'userIsAdmin'=>$userIsAdmin,'Module_ID'=>Config::get('Constant.MODULE.ID')])
                        </div>
                    </div>
                </div><!-- end card-body -->

                <!-- Modal -->
            @else
                @if (File::exists(base_path() . '/resources/views/powerpanel/partials/addrecordsection.blade.php') != null)
                @include('powerpanel.partials.addrecordsection',['type'=>Config::get('Constant.MODULE.TITLE'), 'adUrl' => url('powerpanel/email-log/add')])
                @endif
            @endif

        </div><!-- end card -->
    </div>
    <!-- end col -->
</div>

<div class="new_modal modal fade DetailsEmailLog" tabindex="-1" aria-hidden="true">
</div>

@if (File::exists(base_path() . '/resources/views/powerpanel/partials/deletePopup.blade.php') != null)
    @include('powerpanel.partials.deletePopup')
@endif

@endsection


@section('scripts')
    <script type="text/javascript">
        window.site_url = '{!! url("/") !!}';
        var DELETE_URL = '{!! url("/powerpanel/email-log/DeleteRecord") !!}';
        var settingarray = jQuery.parseJSON('{!!$settingarray!!}');
    </script>
    <script src="{{ $CDN_PATH.'resources/global/plugins/jquery-cookie-master/src/jquery.cookie.js' }}" type="text/javascript"></script>
    <script src="{{ $CDN_PATH.'resources/global/scripts/datatable.js' }}" type="text/javascript"></script>
    <script src="{{ $CDN_PATH.'resources/global/plugins/datatables/datatables.min.js' }}" type="text/javascript"></script>
    <script src="{{ $CDN_PATH.'resources/global/plugins/datatables/plugins/bootstrap/datatables.bootstrap.js' }}" type="text/javascript"></script>
    <script src="{{ $CDN_PATH.'resources/pages/scripts/packages/email_log/table-email-log-ajax.js?v='.time() }}" type="text/javascript"></script>
    <script src="{{ $CDN_PATH.'resources/pages/scripts/custom.js' }}" type="text/javascript"></script>
@endsection