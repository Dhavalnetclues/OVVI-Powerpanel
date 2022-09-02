@extends('powerpanel.layouts.app')
@section('title')
{{Config::get('Constant.SITE_NAME')}} - PowerPanel
@endsection
@section('css')
{{-- <link href="{{ $CDN_PATH.'assets/global/plugins/menu-loader/style.css' }}" rel="stylesheet" type="text/css"/>
<style type="text/css">
    .loading {
        height:20px;
        padding:0 0 0 0;
        position:relative;
        top:-5px;
        left:-15px;
    }
</style> --}}
@endsection
@section('content')
@php
$netquick_admin = auth()->user()->hasRole('netquick_admin');
$settings = json_decode(Config::get("Constant.MODULE.SETTINGS"));
$ignoreList = ['Front Home','General Setting'];
$ignorePermission = ['settings-module-setting','settings-recent-activities','workflow-publish'];
$action = [
'Add',
'Delete',
'Edit',
'List',
'Publish'
];
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
            
                    @if(isset($role))
                    {!! Form::model($role, ['id'=>'frmRole','method' => 'PATCH','route' => ['powerpanel.roles.update', $role->id]]) !!}
                    @else
                    {!! Form::open(array('route' => 'powerpanel.roles.add','method'=>'POST','id'=>'frmRole')) !!}
                    @endif
                    <div class="card">
			                <div class="card-body p-30">
			                    <div class="row mt-1">
			                        <div class="col-lg-6 col-sm-12">
			                            <div class="{{ $errors->has('name') ? ' has-error' : '' }} form-md-line-input cm-floating">
			                                <label class="form-label focus-none" for="name">{{ trans('rolemanager::template.common.name') }}  <span aria-required="true" class="required"> * </span></label>
			                                {!! Form::text('name', isset($role->display_name) ? $role->display_name : old('name'), array('maxlength'=>'150','class' => 'form-control titlespellingcheck','autocomplete'=>'off')) !!}
			                                <span style="color: red;">{{ $errors->first('name') }}</span>
			                            </div>
			                        </div>
			                        <div class="col-lg-6 col-sm-12">
			                            <div class="@if($errors->first('varSector')) has-error @endif form-md-line-input cm-floating">
			                                @if(isset($role->varSector) && ($role->varSector != $role->varSector))
			                                @php $Class_varSector = " highlitetext"; @endphp
			                                @else
			                                @php $Class_varSector = ""; @endphp
			                                @endif
			                                @include('shiledcmstheme::powerpanel.partials.sector-dropdown', ['selected_sector' => isset($role->varSector)?$role->varSector:'','Class_varSector' => $Class_varSector])
			                                <span class="help-block">{{ $errors->first('varSector') }}</span>
			                            </div>
			                        </div>
			                        {{ Form::hidden('rolename', isset($role->name) ? $role->name :'') }}
			                        
			                        <div class="col-md-12">
			                            @if($isAdmin)
			                            	<div class="mb-2">
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
			                        </div>
			                    </div>

			                    <ul class="nav nav-tabs nav-tabs-custom nav-primary fs-16 mb-3 permission-tab" role="tablist">
				                        <li class="nav-item">
				                            <a class="nav-link active" data-bs-toggle="tab" href="#role1" role="tab" aria-selected="true">General</a>
				                        </li>
				                        <li class="nav-item">
				                            <a class="nav-link" data-bs-toggle="tab" href="#role2" role="tab" aria-selected="false">Other</a>
				                        </li>
				                    </ul>
				                    {{-- @if(isset($role)) --}}
				                    <div class="tab-content text-muted">
				                        <div class="tab-pane active" id="role1" role="tabpanel">
				                            <div class="permission-table table-responsive">
				                                <table class="table table-hover">
				                                    <thead class="table-light">                                        
				                                        <tr>
				                                            <th>Module/Access</th>
				                                            <th>All</th>
				                                            <th>Create</th>
				                                            <th>Delete</th>
				                                            <th>Edit</th>
				                                            <th>List</th>
				                                            <th>Publish</th>
				                                        </tr>
				                                    </thead>
				                                    <tbody>
				                                    	@php
				                                      //$grpIdentity =  preg_replace('/[^a-zA-Z0-9\']/', '-', strtolower($grp));
				                                      $perList = [];
				                                      $otherPerList = [];
				                                      @endphp
				                                    	@foreach($permission as $grp => $group)                                    	
					                                      	@if(is_array($group))	                                      		
														                				@foreach($group as $moduleTitle=>$module)
														                         	@if(is_array($module))
														                         		@php
														                         		$moduleTitle = preg_replace('/[^a-zA-Z0-9\']/', ' ', ucwords($moduleTitle));

																												$perList[$moduleTitle]['add']=null;
																												$perList[$moduleTitle]['delete']=null;
																												$perList[$moduleTitle]['edit']=null;
																												$perList[$moduleTitle]['list']=null;
																												$perList[$moduleTitle]['publish']=null;
														                         		foreach($module as $permit){														                         			
															                         		if(is_array($permit) && (auth()->user()->can($permit['name']) || $netquick_admin)){
														                         				if(!in_array($moduleTitle, $ignoreList)){
								                                            		$permit['display_name'] = str_replace('per_', '', $permit['display_name']);
								                                            		$permit['display_name'] = ucwords($permit['display_name']);
								                                            		if(in_array($permit['display_name'], $action)){
									                                            		if(empty($perList[$moduleTitle]['add'])){$perList[$moduleTitle]['add']=$permit['display_name']=='Add'?$permit:null;}
									                                            		if(empty($perList[$moduleTitle]['delete'])){$perList[$moduleTitle]['delete']=$permit['display_name']=='Delete'?$permit:null;}
									                                            		if(empty($perList[$moduleTitle]['edit'])){$perList[$moduleTitle]['edit']=$permit['display_name']=='Edit'?$permit:null;}
									                                            		if(empty($perList[$moduleTitle]['list'])){$perList[$moduleTitle]['list']=$permit['display_name']=='List'?$permit:null;}
									                                            		if(empty($perList[$moduleTitle]['publish'])){$perList[$moduleTitle]['publish']=$permit['display_name']=='Publish'?$permit:null;}
								                                            		}
												                                    	}elseif($moduleTitle != 'Front Home'){
												                                    		$keyPermit = preg_replace('/[^a-zA-Z0-9\']/', '-', strtolower($permit['display_name']));
												                                    		$permit['display_name'] = str_replace('per_', ' ', $permit['display_name']);
												                                    		$permit['display_name'] = str_replace('-', ' ', $permit['display_name']);
												                                    		$permit['display_name'] = str_replace('_', ' ', $permit['display_name']);
								                                            		$permit['display_name'] = ucwords($permit['display_name']);
												                                    		if(empty($otherPerList[$moduleTitle][$keyPermit])){
												                                    			$otherPerList[$moduleTitle][$keyPermit]=$permit;
												                                    		}
												                                    	}
																														}
																													}
							                                      		@endphp
							                                        @endif
										                                 @endforeach
				                                        	@endif
				                                      @endforeach
				                                      
				                                      @foreach($perList as $key => $permits)
				                                      	
				                                      	<tr class="permit-row">
				                                          <td class="fw-medium">{{$key}}</td>
				                                          <td>
				                                              <div class="form-check ps-0">
				                                                  <input class="form-check-input rounded-circle fs-16 ms-0 mt-0 per-check-all" type="checkbox"/>
				                                              </div>
				                                          </td>				                                            
																									
																									@foreach($permits as $permit)
																										@if(!empty($permit))
																										<td>
					                                              <div class="form-check ps-0" title="{{$permit['display_name']}}">
					                                              		<input class="form-check-input rounded-circle fs-16 ms-0 mt-0 permitip" id="per-{{$permit['id']}}" value="{{$permit['id']}}" name="permission[{{$permit['id']}}]" type="checkbox" @if(isset($rolePermissions)) {{in_array($permit['id'], $rolePermissions) ? 'checked' : ''}} @endif>
					                                              </div>
					                                          </td>
						                                        @else
						                                        	<td><i class="ri-subtract-line fs-20 text-muted"></i></td>
						                                        @endif
				                                          @endforeach

																								</tr>
																								
				                                      @endforeach

				                                    </tbody>
				                                </table>
				                            </div>
				                        </div>
				                        <div class="tab-pane" id="role2" role="tabpanel">				                        	
				                            <div class="permission-table table-responsive">
				                                <table class="table table-hover">
				                                    <thead class="table-light">                                        
				                                        <tr>
				                                            {{-- <th>Module/Name</th>
				                                            <th>All</th> --}}
				                                            @foreach($otherPerList as $key => $oPermits)
				                                            	<th>{{$key}}</th>
				                                            @endforeach
				                                        </tr>
				                                    </thead>
				                                    <tbody>

				                                        @foreach($otherPerList as $key=>$oPermits)
				                                      		@foreach($oPermits as $key=>$oPermit)
																										<tr class="permit-row">
																											@if(!empty($oPermit))
																												<td>
							                                              <div class="form-check ps-0" title="{{$oPermit['display_name']}}">
							                                              	<label class="m-0">
							                                              		<input class="form-check-input rounded-circle fs-16 ms-0 mt-0 me-1 permitip" id="per-{{$oPermit['id']}}" value="{{$oPermit['id']}}" name="permission[{{$oPermit['id']}}]" type="checkbox" @if(isset($rolePermissions)) {{in_array($oPermit['id'], $rolePermissions) ? 'checked' : ''}} @endif>
							                                              		{{$oPermit['display_name']}}
							                                              	</label>
							                                              </div>
							                                          </td>
							                                        @else
							                                        	<td><i class="ri-subtract-line fs-20 text-muted"></i></td>
							                                        @endif
						                                        </tr>
				                                          @endforeach
				                                      @endforeach
				                                    </tbody>
				                                </table>
				                            </div>
				                        </div>
				                    </div>
				                    {{-- @endif --}}

			                    <div class="row">
			                    	<div class="col-md-12">
			                            <div class="form-actions">
			                                <div class="row">
			                                    <div class="col-md-12">
			                                        <button type="submit" name="saveandedit" formmethod="post" class="btn btn-primary bg-gradient waves-effect waves-light btn-label me-1" value="saveandedit">
			                                            <div class="flex-shrink-0">
			                                                <i class="ri-save-line label-icon align-middle fs-20 me-2"></i>
			                                            </div>
			                                            {!! trans('rolemanager::template.common.saveandedit') !!}
			                                        </button>
			                                        <button type="submit" name="saveandexit" formmethod="post" class="btn btn-secondary bg-gradient waves-effect waves-light btn-label me-1" value="saveandexit">
			                                            <div class="flex-shrink-0">
			                                                <i class="ri-save-3-line label-icon align-middle fs-20 me-2"></i>
			                                            </div>
			                                            {!! trans('rolemanager::template.common.saveandexit') !!}
			                                        </button>
			                                        <a class="btn btn-danger bg-gradient waves-effect waves-light btn-label me-1" href="{{url('powerpanel/roles')}}">
			                                            <div class="flex-shrink-0">
			                                                <i class="ri-close-line label-icon align-middle fs-20 me-2"></i>
			                                            </div>
			                                            {{ trans('rolemanager::template.common.cancel') }}
			                                        </a>
			                                    </div>
			                                </div>
			                            </div>
			                        </div>
			                    </div>
											</div>
								    </div>
                   
                    {!! Form::close() !!}
                

            


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
<script src="{{ $CDN_PATH.'resources/pages/scripts/packages/rolemanager/role_validations.js?v='.time() }}" type="text/javascript"></script>
@endsection