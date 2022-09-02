@extends('powerpanel.layouts.app')
@section('title')
{{Config::get('Constant.SITE_NAME')}} - PowerPanel
@stop
@section('breadcrumb')
@section('content')
@php $settings = json_decode(Config::get("Constant.MODULE.SETTINGS")); @endphp
@include('powerpanel.partials.breadcrumbs')
<div class="row">
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
		<div class="portlet light bordered">
			<div class="portlet-body">
				<div class="tabbable tabbable-tabdrop">
					<div class="tab-content settings">
						<div class="tab-pane active" id="general">
						<div class="row">
								<div class="col-md-12">
									<div class="portlet-body form_pattern">
										{!! Form::open(['method' => 'post','id'=>'frmStaticBlock','class'=> 'static_block_form']) !!}
										<div class="form-group @if($errors->first('title')) has-error @endif form-md-line-input">
											{!! Form::text('title', isset($staticBlocks->varTitle)?$staticBlocks->varTitle:old('title'), array('maxlength' => '150', 'class' => 'form-control hasAlias maxlength-handler','placeholder' => trans('static-blocks::template.common.title'), 'data-url' => 'powerpanel/static-block','autocomplete'=>'off')) !!}
											<label class="form_title" for="title">{{ trans('static-blocks::template.common.title') }} <span aria-required="true" class="required"> * </span></label>
											<span class="help-block">
												{{ $errors->first('title') }}
											</span>
										</div>
										<!-- code for alias -->
										{!! Form::hidden(null, null, array('class' => 'hasAlias','data-url' => 'powerpanel/static-block')) !!}
										{!! Form::hidden('alias', isset($staticBlocks->alias->varAlias)?$staticBlocks->alias->varAlias:old('alias'), array('class' => 'aliasField')) !!}
										{!! Form::hidden('oldAlias', isset($staticBlocks->alias->varAlias)?$staticBlocks->alias->varAlias:old('alias')) !!}
										{{--<div class="form-group" id="shortCodeDiv" style="@php echo (!isset($staticBlocks))?'display:none;':''@endphp">
											<label class="form_title" for="shortcode">Shortcode:</label>
											<span id="shortCode">{{ isset($staticBlocks)?$staticBlocks->alias->varAlias:'' }}</span>
										</div> --}}
										<div class="form-group alias-group hide">
											<label class="form_title" for="Url">{{ trans('static-blocks::template.common.url') }} :</label>
											<a href="javascript:void;" class="alias">{!! url("/") !!}</a>
											<a href="javascript:void(0);" class="editAlias" title="Edit">
											<i class="ri-pencil-line"></i></a>
											&nbsp;<a class="without_bg_icon openLink" title="Open Link" target="_blank" href="{{url('static-block/'.isset($staticBlocks) && isset($staticBlocks->alias->varAlias)?$staticBlocks->alias->varAlias:'')}}"><i class="ri-external-link-line" aria-hidden="true"></i></a>
										</div>
										<span class="help-block">
											{{ $errors->first('alias') }}
										</span>
										<div class="row">
											<div class="col-md-12">
												<div class="form-group @if($errors->first('intChildMenu')) has-error @endif">
													<label class="form_title" for="intChildMenu">Parent Static Block </label>
													<select class="form-control bs-select select2" name="intChildMenu" id="intChildMenu">
														<option value=""> --- Select Parent Static Block --- </option>
														@foreach($DropDownList as $List)
															@php $staticParentSelected=''; @endphp
															@if(isset($staticBlocks->intChildMenu))
																@if($List->id == $staticBlocks->intChildMenu)
																	@php
																		$staticParentSelected ='selected="selected"';
																	@endphp
																@endif
															@endif
														@if(isset($staticBlocks))
															@if($List->id != $staticBlocks->id )
															<option value="{{ $List->id }}" {{ $staticParentSelected }}> {{ $List->varTitle }} </option>
															@endif
															@else
															<option value="{{ $List->id }}" {{ $staticParentSelected }}> {{ $List->varTitle }} </option>
															@endif
														@endforeach
													</select>
													<span class="help-block">{{ $errors->first('intChildMenu') }} </span> </div>
												</div>
											</div>
											<!-- here start My code -->

											@include('powerpanel.partials.imageControl',['type' => 'single','label' => trans('static-blocks::template.common.selectimage') ,'data'=> isset($staticBlocks)?$staticBlocks:null , 'id' => 'staticBlocks_image', 'name' => 'img_id', 'settings' => $settings, 'width' => '500', 'height' => '500'])

											@include('powerpanel.partials.videoControl',['type' => 'single' ,'label' => 'Select Video' ,'data'=> isset($staticBlocks)?$staticBlocks:null, 'id' => 'staticBlocks_video', 'videoData'=> isset($videoDataForSingle)?$videoDataForSingle:null, 'name' => 'video_id'])


													<!-- code for alias -->
													<div class="form-group @if($errors->first('description')) has-error @endif">
														<label class="form_title" for="title">{{ trans('static-blocks::template.common.description') }}</label>
														{!! Form::textarea('description', isset($staticBlocks->txtDescription)?$staticBlocks->txtDescription:old('description'), array('placeholder' => trans('static-blocks::template.common.description'),'class' => 'form-control','id'=>'txtDescription')) !!}
														<span class="help-block">{{ $errors->first('description') }}</span>
													</div>
													<!-- Start external link here -->
													<div class="row">
														<div class="col-md-12">
															<div class="form-group @if($errors->first('varExternalLink')) has-error @endif form-md-line-input">
																{!! Form::text('varExternalLink', isset($staticBlocks->varExternalLink)?$staticBlocks->varExternalLink:old('varExternalLink'), array('class' => 'form-control','placeholder' => "External Link",'autocomplete'=>'off')) !!}
																<label class="form_title" for="site_external_link">External Link </label>
																<span class="help-block">
																	{{ $errors->first('varExternalLink') }}
																</span>
															</div>
														</div>
													</div>
													<!-- end external link here -->
													<h3 class="form-section">{{ trans('static-blocks::template.common.displayinformation') }}</h3>
													@include('powerpanel.partials.displayInfo',['display' => isset($staticBlocks->chrPublish)?$staticBlocks->chrPublish:null])
													<div class="form-actions">
														<div class="row">
															<div class="col-md-12">
																<button type="submit" name="saveandedit" class="btn btn-green-drake" value="saveandedit">{!! trans('static-blocks::template.common.saveandedit') !!}</button>
																<button type="submit" name="saveandexit" class="btn btn-green-drake" value="saveandexit">{!! trans('static-blocks::template.common.saveandexit') !!}</button>
																<a class="btn red btn-outline" href="{{ url('powerpanel/static-block') }}">{{ trans('static-blocks::template.common.cancel') }}</a>
															</div>
														</div>
													</div>
													{!! Form::close() !!}
												</div>
											</div>
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
			@endsection
			@section('scripts')
			<script src="{{ $CDN_PATH.'resources/pages/scripts/static_block_validation.js' }}" type="text/javascript"></script>
			@include('powerpanel.partials.ckeditor')
			<script type="text/javascript">
				window.site_url =  '{!! url("/") !!}';
				var user_action = "{{ isset($staticBlocks)?'edit':'add' }}";
				var moduleAlias = 'static-block';
			</script>
			<script src="{{ $CDN_PATH.'resources/pages/scripts/custom.js' }}" type="text/javascript"></script>
			<script src="{{ $CDN_PATH.'resources/global/plugins/custom-alias/alias-generator.js' }}" type="text/javascript"></script>
			<script src="{{ $CDN_PATH.'resources/global/plugins/bootstrap-maxlength/bootstrap-maxlength.min.js' }}" type="text/javascript"></script>
			<script type="text/javascript">
			$('.maxlength-handler').maxlength({
				limitReachedClass: "label label-danger",
				alwaysShow: true,
				threshold: 5,
				twoCharLinebreak: false,
				appendToParent:true
			});
			</script>
			@endsection
