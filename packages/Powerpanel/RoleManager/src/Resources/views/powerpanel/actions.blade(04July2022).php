@extends('powerpanel.layouts.app')
@section('title')
{{Config::get('Constant.SITE_NAME')}} - PowerPanel
@endsection
@section('css')
<link href="{{ $CDN_PATH.'assets/global/plugins/menu-loader/style.css' }}" rel="stylesheet" type="text/css"/>
<style type="text/css">
    .loading {
        height:20px;
        padding:0 0 0 0;
        position:relative;
        top:-5px;
        left:-15px;
    }
</style>
@endsection
@section('content')
@php
$settings = json_decode(Config::get("Constant.MODULE.SETTINGS"));
$ignoreList = ['Front Home','Sitemap'];
$ignorePermission = ['settings-module-setting','settings-recent-activities','workflow-publish'];
@endphp

<div class="row settings">
    <div class="col-sm-12">
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

        <div class="live-preview">
            <div class="card">
                <div class="card-body p-30">
                    @if(isset($role))
                    {!! Form::model($role, ['id'=>'frmRole','method' => 'PATCH','route' => ['powerpanel.roles.update', $role->id]]) !!}
                    @else
                    {!! Form::open(array('route' => 'powerpanel.roles.add','method'=>'POST','id'=>'frmRole')) !!}
                    @endif
                    <div class="row">
                        <div class="col-md-12">
                            <div class="{{ $errors->has('name') ? ' has-error' : '' }} form-md-line-input cm-floating">
                                <label class="form-label focus-none" for="name">{{ trans('rolemanager::template.common.name') }}  <span aria-required="true" class="required"> * </span></label>
                                {!! Form::text('name', isset($role->display_name) ? $role->display_name : old('name'), array('maxlength'=>'150','class' => 'form-control input-sm titlespellingcheck','placeholder' => trans('rolemanager::template.common.name'),'autocomplete'=>'off')) !!}
                                <span style="color: red;">{{ $errors->first('name') }}</span>
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="@if($errors->first('sector')) has-error @endif form-md-line-input cm-floating">
                                @if(isset($role->varSector) && ($role->varSector != $role->varSector))
                                @php $Class_varSector = " highlitetext"; @endphp
                                @else
                                @php $Class_varSector = ""; @endphp
                                @endif
                                @include('shiledcmstheme::powerpanel.partials.sector-dropdown', ['selected_sector' => isset($role->varSector)?$role->varSector:'','Class_varSector' => $Class_varSector])
                                <span class="help-block">{{ $errors->first('sector') }}</span>
                            </div>
                        </div>
                        {{ Form::hidden('rolename', isset($role->name) ? $role->name :'') }}
                        
                        <div class="col-md-12 pb-30">
                            @if($isAdmin)
                            <div class="mb-3">
                                <label class="form-label">Is Admin Role?
                                    <span class="form-switch checked_off_on title_checked">
                                        @php $checked = (isset($role->chrIsAdmin) && $role->chrIsAdmin == 'Y') ? 'checked' : ''; @endphp
                                        @if (File::exists(base_path() . '/resources/views/powerpanel/partials/bootstrap-switch.blade.php') != null)
                                            @include('powerpanel.partials.bootstrap-switch',['name'=>'isadmin', 'id'=>'isadmin', 'checked'=>$checked])
                                        @endif
                                    </span>
                                </label>
                            </div>
                            @endif

                            @if(isset($role))
                                <div class="{{ $errors->has('permission') ? ' has-error' : '' }} ">
                                    <label class="form-label focus-none" for="permission">{{ trans('rolemanager::template.common.permission') }}  <span aria-required="true" class="required"> * </span></label>
                                    <div class="clearfix" style="height:5px;"></div>
                                    <div class="row">
                                        <div class="col-md-12">
                                            @foreach($permission as $grp => $group)
                                            @if(strtolower($group['group']) != "logs" || (auth()->user()->hasRole('netquick_admin')))
                                            <div class="grp-sec">
                                                @php
                                                $grpIdentity =  preg_replace('/[^a-zA-Z0-9\']/', '-', strtolower($group['group']));
                                                @endphp
                                                <label class="form-label border_bottom">{{$group['group'] }}</label>
                                                <div class="row {{ $grpIdentity }}">
                                                    @foreach($group as $key => $permissions)
                                                    @if(is_array($permissions))
                                                    @php $permit=[]; $moduleOn=[]; @endphp
                                                    @foreach($permissions as $index=>$pval)
                                                    @if(isset($pval['name']))
                                                    @if(auth()->user()->can($pval['name']) || auth()->user()->hasRole('netquick_admin'))
                                                    @php
                                                    array_push($permit, $pval['name']);
                                                    if(in_array($pval['id'], $rolePermissions)){
                                                    array_push($moduleOn, $pval['name']);
                                                    }
                                                    if(count($moduleOn) == 1){
                                                    if (strpos($moduleOn[0], '-reviewchanges') !== false) {
                                                    $moduleOn = [];
                                                    }
                                                    }
                                                    @endphp
                                                    @endif
                                                    @endif
                                                    @endforeach
                                                    @if(count($permit)>0)
                                                    @if(!in_array($key, $ignoreList))
                                                    @php
                                                    $moduleIdentity =  preg_replace('/[^a-zA-Z0-9\']/', '-', strtolower($key));
                                                    @endphp
                                                    <div class="col-md-4 mb-3">
                                                        <div class="permissions_list">
                                                            <label class="form-label">
                                                                {{$key}}
                                                                <span class="form-switch checked_off_on activation">
                                                                    @php $checked = (count($moduleOn) > 0)?'checked' : '' @endphp
                                                                    @if (File::exists(base_path() . '/resources/views/powerpanel/partials/bootstrap-switch.blade.php') != null)
                                                                        @include('powerpanel.partials.bootstrap-switch',['name'=>'active', 'id'=>$moduleIdentity, 'data_off_text'=>'In active', 'data_on_text'=>'Active', 'checked'=>$checked, 'class'=>'module-activation'])
                                                                    @endif
                                                                </span>
                                                            </label>
                                                            <span class="right_permis {{ $moduleIdentity }}">
                                                                @foreach($permissions as $index=>$value)
                                                                @if(isset($value['name']))
                                                                @if($value['display_name']=='per_reviewchanges')
                                                                <input type="hidden"  name="reviewPermissions[]" value="{{$value['id']}}">
                                                                @endif
                                                                @if((auth()->user()->can($value['name']) && $value['display_name']!='per_reviewchanges' && !in_array($value['name'], $ignorePermission) ) || ( $value['display_name']!='per_reviewchanges' && !in_array($value['name'], $ignorePermission) && auth()->user()->hasRole('netquick_admin') ))
                                                                <span class="md-checkbox {{$value['display_name']}} menu_active">
                                                                    <input id="per-{{$value['id']}}" style="opacity:0" value="{{$value['id']}}" name="permission[{{$value['id']}}]" class="md-check" type="checkbox" {{in_array($value['id'], $rolePermissions) ? 'checked' : ''}}>
                                                                    <label for="per-{{$value['id']}}">
                                                                        <span class="inc"></span>
                                                                        <span class="check tooltips" data-bs-toggle="tooltip" data-bs-placement="top" data-bs-content="Revoke {{ucwords(str_replace('-',' ', $value['description']))}}"></span>
                                                                        <span class="box tooltips" data-bs-toggle="tooltip" data-bs-placement="top" data-bs-content="Grant {{ucwords(str_replace('-',' ', $value['description']))}}"></span>
                                                                        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span>
                                                                @endif
                                                                @endif
                                                                @endforeach
                                                            </span>
                                                        </div>
                                                    </div>
                                                    @endif
                                                    @endif
                                                    @endif
                                                    @endforeach
                                                </div>
                                            </div>
                                            @endif
                                            @endforeach
                                        </div>
                                    </div>
                                    <span style="color: red;">{{ $errors->first('permission') }}</span>
                                </div>
                            @else
                                <div class="{{ $errors->has('permission') ? ' has-error' : '' }} ">
                                    <label class="form-label focus-none" for="permission">{{ trans('rolemanager::template.common.permission') }}  <span aria-required="true" class="required"> * </span></label>
                                    <div class="clearfix" style="height:5px;"></div>
                                    <div class="row">
                                        <div class="col-md-12">
                                            @foreach($permission as $grp => $group)
                                            @if(strtolower($group['group']) != "logs" || (auth()->user()->hasRole('netquick_admin')))
                                            <div class="grp-sec">
                                                @php
                                                $grpIdentity = preg_replace('/[^a-zA-Z0-9\']/', '-', strtolower($group['group']));
                                                @endphp
                                                <label class="form-label border_bottom">{{$group['group'] }}</label>
                                                <div class="row {{ $grpIdentity }}">
                                                    @foreach($group as $key => $permissions)
                                                    @if(is_array($permissions))
                                                    @php $permit=[]; @endphp
                                                    @foreach($permissions as $index=>$pval)
                                                    @if(isset($pval['name']))
                                                    @if(auth()->user()->can($pval['name']) || auth()->user()->hasRole('netquick_admin'))
                                                    @php
                                                    array_push($permit, $pval['name']);
                                                    @endphp
                                                    @endif
                                                    @endif
                                                    @endforeach
                                                    @if(count($permit)>0)
                                                    @if(!in_array($key, $ignoreList) || (auth()->user()->hasRole('netquick_admin') && strtolower($key) == "logs"))
                                                    @php
                                                    $moduleIdentity =  preg_replace('/[^a-zA-Z0-9\']/', '-', strtolower($key));
                                                    @endphp
                                                    <div class="col-md-4 mn-3">
                                                        <div class="permissions_list">
                                                            <label class="form-label">
                                                                <span class="form-switch checked_off_on activation">
                                                                    @if (File::exists(base_path() . '/resources/views/powerpanel/partials/bootstrap-switch.blade.php') != null)
                                                                        @include('powerpanel.partials.bootstrap-switch',['name'=>'active', 'id'=>$moduleIdentity, 'data_off_text'=>'In active', 'data_on_text'=>'Active', 'class'=>'module-activation'])
                                                                    @endif
                                                                </span>
                                                                {{$key}}</label>
                                                            <span class="right_permis {{ $moduleIdentity }}">
                                                                @foreach($permissions as $index=>$value)
                                                                @if(isset($value['display_name']) && $value['display_name']=='per_reviewchanges')
                                                                <input type="hidden"  name="reviewPermissions[]" value="{{$value['id']}}">
                                                                @endif
                                                                @if(isset($value['name']) && $value['display_name']!='per_reviewchanges' && !in_array($value['name'], $ignorePermission) )
                                                                {{-- @if(isset($value['name'])) --}}
                                                                @if(auth()->user()->can($value['name']) || auth()->user()->hasRole('netquick_admin'))
                                                                <span class="md-checkbox {{$value['display_name']}} menu_active">
                                                                    <input id="per-{{$value['id']}}" style="opacity:0" value="{{$value['id']}}" name="permission[{{$value['id']}}]" class="md-check" type="checkbox">
                                                                    <label for="per-{{$value['id']}}">
                                                                        <span class="inc"></span>
                                                                        <span class="check tooltips" data-bs-toggle="tooltip" data-bs-placement="top" data-bs-content="Revoke {{ucwords(str_replace('-',' ', $value['description']))}}"></span>
                                                                        <span class="box tooltips" data-bs-toggle="tooltip" data-bs-placement="top" data-bs-content="Grant {{ucwords(str_replace('-',' ', $value['description']))}}"></span>
                                                                        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span>
                                                                @endif
                                                                @endif
                                                                @endforeach
                                                            </span>
                                                        </div>
                                                    </div>
                                                    @endif
                                                    @endif
                                                    @endif
                                                    @endforeach
                                                </div>
                                            </div>
                                            @endif
                                            @endforeach
                                        </div>
                                    </div>
                                    <span style="color: red;">
                                        {{ $errors->first('permission') }}
                                    </span>
                                </div>
                            @endif
                        </div>

                        <div class="col-md-12">
                            <div class="form-actions">
                                <div class="row">
                                    <div class="col-md-12">
                                        <button type="submit" name="saveandedit" class="btn btn-primary bg-gradient waves-effect waves-light btn-label me-1" value="saveandedit">
                                            <div class="d-flex">
                                                <div class="flex-shrink-0">
                                                    <i class="ri-save-line label-icon align-middle fs-20 me-2"></i>
                                                </div>
                                                <div class="flex-grow-1">
                                                    {!! trans('rolemanager::template.common.saveandedit') !!}
                                                </div>
                                            </div>
                                        </button>
                                        <button type="submit" name="saveandexit" class="btn btn-secondary bg-gradient waves-effect waves-light btn-label me-1" value="saveandexit">
                                            <div class="d-flex">
                                                <div class="flex-shrink-0">
                                                    <i class="ri-save-3-line label-icon align-middle fs-20 me-2"></i>
                                                </div>
                                                <div class="flex-grow-1">
                                                    {!! trans('rolemanager::template.common.saveandexit') !!}
                                                </div>
                                            </div>
                                        </button>
                                        <a class="btn btn-danger bg-gradient waves-effect waves-light btn-label me-1" href="{{url('powerpanel/roles')}}">
                                            <div class="d-flex">
                                                <div class="flex-shrink-0">
                                                    <i class="ri-close-line label-icon align-middle fs-20 me-2"></i>
                                                </div>
                                                <div class="flex-grow-1">
                                                    {{ trans('rolemanager::template.common.cancel') }}
                                                </div>
                                            </div>
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    {!! Form::close() !!}
                </div>
            </div>
        </div>
    </div>
</div>
<div class="clearfix"></div>
@endsection
@section('scripts')
<script type="text/javascript">
    var rootUrl = "{{ URL::to('/') }}";
    var moduleAlias = "";
    var editing = false;
            @if (isset($role -> chrIsAdmin))
            editing = true;
            @endif
</script>
<script src="{{ $CDN_PATH.'resources/global/plugins/bootstrap-maxlength/bootstrap-maxlength.min.js' }}" type="text/javascript"></script>
<script src="{{ $CDN_PATH.'assets/global/plugins/menu-loader/jquery-loader.js' }}" type="text/javascript"></script>
<script src="{{ $CDN_PATH.'resources/pages/scripts/packages/rolemanager/role_validations.js' }}" type="text/javascript"></script>
@endsection