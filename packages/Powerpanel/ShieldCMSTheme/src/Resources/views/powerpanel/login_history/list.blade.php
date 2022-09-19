@extends('powerpanel.layouts.app')
 @section('title')
     {{Config::get('Constant.SITE_NAME')}} - PowerPanel
 @endsection
 @section('css')
     <link href="{{ $CDN_PATH.'resources/global/plugins/highslide/highslide.css' }}" rel="stylesheet" type="text/css"/>
 @endsection
@section('content')

<script type="text/javascript">
    window.site_url = '{!! url("/") !!}';
    var DELETE_URL = '{!! url("/powerpanel/login-history/DeleteRecord") !!}';

    var showChecker = true;
    @if (!$userIsAdmin)
    showChecker = false;
    @endif
</script>

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
                                    ['Identity_Name'=>'flag','TabIndex'=>'1','Name'=>'Flag'],
                                    ['Identity_Name'=>'country','TabIndex'=>'2','Name'=>'Country'],
                                    ['Identity_Name'=>'name','TabIndex'=>'3','Name'=>'Name'],
                                    ['Identity_Name'=>'email','TabIndex'=>'4','Name'=>'Email'],
                                    ['Identity_Name'=>'ip','TabIndex'=>'5','Name'=>'IP'],
                                    ['Identity_Name'=>'login time','TabIndex'=>'6','Name'=>'Login Time'],
                                    ['Identity_Name'=>'logOut time','TabIndex'=>'7','Name'=>'LogOut Time'],
                                 ],
                                 'DataTableHead'=>[
                                    ['Title'=>'Flag','Align'=>'left'],
                                    ['Title'=>'Country','Align'=>'left'],
                                    ['Title'=>'Name','Align'=>'left'],
                                    ['Title'=>'Publish','Align'=>'left'],
                                    ['Title'=>'IP','Align'=>'right'],
                                    ['Title'=>'Login Time','Align'=>'right'],
                                    ['Title'=>'LogOut Time','Align'=>'right'],
                                 ]
                             ]
                         ];
                         @endphp
                         @include('powerpanel.partials.datatable-view',['ModuleName'=>'LoginHistory','Permission_Delete'=>'login-history-delete','tablearray'=>$tablearray,'userIsAdmin'=>$userIsAdmin,'Module_ID'=>Config::get('Constant.MODULE.ID')])
                     </div>
                 </div>
             </div><!-- end card-body -->

             <!-- Modal -->
        @endif

     </div><!-- end card -->
 </div>
 <!-- end col -->
</div>


@if (File::exists(base_path() . '/resources/views/powerpanel/partials/deletePopup.blade.php') != null)
@include('powerpanel.partials.deletePopup')
@endif

<div class="new_modal modal fade" id="noRecords" tabindex="-1" role="basic" aria-hidden="true">
 <div class="modal-dialog">
     <div class="modal-vertical">
         <div class="modal-content">
             <div class="modal-header">
                 Alert
                 <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"> </button>
             </div>
             <div class="modal-body text-center">No Records to export!</div>
             <div class="modal-footer">
                 <button type="button" class="btn btn-primary" data-bs-dismiss="modal">OK</button>
             </div>
         </div>
     </div>
 </div>
</div>

<div class="new_modal modal fade" id="selectedRecords" tabindex="-1" role="basic" aria-hidden="true">
 <div class="modal-dialog">
     <div class="modal-vertical">	
         <div class="modal-content">
             <div class="modal-header">
                 Alert
                 <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"> </button>
             </div>
             <div class="modal-body text-center">
                 <div class="row">
                     <div class="col-12 mb-3">
                         Which records do you want to export?
                     </div>
                     <div class="col-12">
                         <div class="form-check form-check-inline">
                             <input class="form-check-input" type="radio" value="selected_records" id="selected_records" name="export_type">
                             <label for="form-check-label">Selected Records</label>
                         </div>
                         <div class="form-check form-check-inline">
                             <input class="form-check-input" type="radio" value="all_records" id="all_records" name="export_type" checked>
                             <label for="form-check-label">All Records</label>
                         </div>
                     </div>
                 </div>
             </div>
             <div class="modal-footer">
                 <button type="button" class="btn btn-primary" id="ExportRecord" data-bs-dismiss="modal">OK</button>
             </div>
         </div>
         <!-- /.modal-content -->
     </div>
 </div>
 <!-- /.modal-dialog -->
</div>

<div class="new_modal modal fade" id="noSelectedRecords" tabindex="-1" role="basic" aria-hidden="true">
 <div class="modal-dialog">
     <div class="modal-vertical">	
         <div class="modal-content">
             <div class="modal-header">
                 Alert
                 <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"> </button>
             </div>
             <div class="modal-body text-center">Please selecte at list one record.</div>
             <div class="modal-footer">
                 <button type="button" class="btn btn-primary" data-bs-dismiss="modal">OK</button>
             </div>
         </div>
         <!-- /.modal-content -->
     </div>
 </div>
 <!-- /.modal-dialog -->
</div>

@endsection


@section('scripts')
<script type="text/javascript">
 window.site_url = '{!! url("/") !!}';
 var MODULE_URL = '{!! url("/powerpanel/login-history/DeleteRecord") !!}';

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
<script src="{{ $CDN_PATH.'resources/pages/scripts/packages/login_history/loginhistory-datatables-ajax.js?v='.time() }}" type="text/javascript"></script>
<script src="{{ $CDN_PATH.'resources/pages/scripts/packages/login_history/loginhistoryfunctions.js?v='.time() }}" type="text/javascript"></script>
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
 var moduleName = 'loginhistory';</script>
@endif

@endsection