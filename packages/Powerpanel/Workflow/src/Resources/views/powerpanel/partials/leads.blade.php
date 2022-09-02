{!! Form::open(['method' => 'post','id'=>'frmWorkflow']) !!}
{!! Form::hidden('workflow_type', 'leads') !!}
<div class="form-body">
	<div class="row">
		<div class="col-md-12">
			<div class="form-group @if($errors->first('title')) has-error @endif form-md-line-input">
				<label class="form_title" class="site_name">{{ trans('workflow::template.common.title') }} <span aria-required="true" class="required"> * </span></label>
				{!! Form::text('title', isset($workflow->varTitle)?$workflow->varTitle:old('title'), array('maxlength' => 150, 'class' => 'form-control hasAlias seoField maxlength-handler','autocomplete'=>'off','data-url' => 'powerpanel/workflow')) !!}
				<span class="help-block">
					{{ $errors->first('title') }}
				</span>
			</div>
		</div>
	</div>
	<div class="flow_form">
		
		
		
		<div class="row">
			<div class="col-md-12">
				<div class="form-group ">
					<div class="user_fill">
						<span>User Fills the form</span>
					</div>
				</div>
				<!-- Arrow Divider -->
				<div class="arrow_line"><span></span></div>
				
				<div class="form-group @if($errors->first('activity')) has-error @endif form-md-line-input">
					<label class="form_title" class="site_name">Activity <span aria-required="true" class="required"> * </span></label>
					<div class="clearfix"></div>
					@if(isset($workflow->varActivity))
					@php $selected = $workflow->varActivity; @endphp
					@elseif(null !== old('activity'))
					@php $selected = old('activity'); @endphp
					@else
					@php $selected = ''; @endphp
					@endif
					<div class="input_box">
						<select id="activity" name="activity" data-sort data-order class="form-control bs-select select2 status_select">
							<option value="" @if($selected == "") selected @endif>Select activity</option>
							<option value="contact-us" {{-- Use varModule name of module table as value --}}  @if($selected == "contact-us") selected @endif>Contact Us</option>
						</select>
					</div>
					<span class="help-block">
						{{ $errors->first('activity') }}
					</span>
				</div>
				<!-- Arrow Divider -->
				<div class="arrow_line"><span></span></div>
				
				<div class="form-group @if($errors->first('action')) has-error @endif form-md-line-input">
					<label class="form_title" class="site_name">Action <span aria-required="true" class="required"> * </span></label>
					<div class="clearfix"></div>
					@if(isset($workflow->varAction))
					@php $selected = $workflow->varAction; @endphp
					@elseif(null !== old('action'))
					@php $selected = old('action'); @endphp
					@else
					@php $selected = ''; @endphp
					@endif
					<div class="input_box">
						<select id="action" name="action" data-sort data-order class="form-control bs-select select2 status_select">
							<option value="" @if($selected == "") selected @endif>Select action</option>
							<option value="contact-lead-received" @if($selected == "contact-lead-received") selected @endif>Lead Recieved</option>
						</select>
					</div>
					<span class="help-block">
						{{ $errors->first('action') }}
					</span>
				</div>
				<!-- Arrow Divider -->
				<div class="arrow_line"><span></span></div>
				
				<div class="after_msg clearfix">
					<div class="left">
						<div class="form-group @if($errors->first('after')) has-error @endif form-md-line-input">
							<label class="form_title" class="site_name">After <span aria-required="true" class="required"> * </span></label>
							<div class="clearfix"></div>
							@if(isset($workflow->varAfter))
							@php $selected = $workflow->varAfter; @endphp
							@elseif(null !== old('after'))
							@php $selected = old('after'); @endphp
							@else
							@php $selected = ''; @endphp
							@endif
							<div class="input_box">
								<select id="after" name="after" data-sort data-order class="form-control bs-select select2 status_select">
									<option value="" @if($selected == "") selected @endif>Select when</option>
									<option value="1" @if($selected == "1") selected @endif>1 Day</option>
									<option value="3" @if($selected == "3") selected @endif>3 Days</option>
									<option value="7" @if($selected == "7") selected @endif>7 Days</option>
								</select>
							</div>
							<span class="help-block">
								{{ $errors->first('after') }}
							</span>
						</div>
					</div>
					<div class="right">
						<div class="form-group @if($errors->first('after_content')) has-error @endif">
							<label class="form_title" for="after_content">Email Content<span aria-required="true" class="required"> * </span></label>
							<div class="clearfix"></div>
							<div class="input_box">
								{!! Form::textarea('after_content',isset($workflow->txtAfter)?$workflow->txtAfter:old('after_content'),array('class' => 'form-control','palceholder'=>'After email content','style'=>'max-height:80px;')) !!}
							</div>
							<span class="help-block">
								{{ $errors->first('after_content') }}
							</span>
						</div>
					</div>
				</div>
				<!-- Arrow Divider -->
				<div class="arrow_line"><span></span></div>
				
				<div class="customer_div">
					<div class="customer_box"><span>Customer Fills Survey</span></div>
					<div class="row_customer">
						<div class="row">
							<div class="col-sm-6 col-xs-6 left">
								<div class="arrow_line"><span></span></div>
								<label class="label_title">If Yes</label>
								<div class="row_inp_lf">
									<div class="form-group @if($errors->first('frequancy_positive')) has-error @endif ">
										<label class="form_title" class="site_name">Send e-mail<span aria-required="true" class="required"> * </span></label>
										@if(isset($workflow->varFrequancyPositive))
										@php $selected = $workflow->varFrequancyPositive; @endphp
										@elseif(null !== old('frequancy_positive'))
										@php $selected = old('frequancy_positive'); @endphp
										@else
										@php $selected = ''; @endphp
										@endif
										<select id="frequancy_positive" name="frequancy_positive" data-sort data-order class="form-control bs-select select2 status_select">
											<option value="" @if($selected == "") selected @endif>Select when</option>
											<option value="0" @if($selected == "0") selected @endif>Immediately</option>
											<option value="1" @if($selected == "1") selected @endif>After 1 Day</option>
											<option value="3" @if($selected == "3") selected @endif>After 3 Days</option>
											<option value="7" @if($selected == "7") selected @endif>After 7 Days</option>
										</select>
										<span class="help-block">
											{{ $errors->first('frequancy_positive') }}
										</span>
									</div>
									<div class="form-group @if($errors->first('yes_content')) has-error @endif">
										<label class="form_title" for="yes_content">Email Content<span aria-required="true" class="required"> * </span></label>
										{!! Form::textarea('yes_content',isset($workflow->txtFrequancyPositive)?$workflow->txtFrequancyPositive:old('yes_content'),array('class' => 'form-control','palceholder'=>'Yes email content','style'=>'max-height:80px;')) !!}
										<span class="help-block">
											{{ $errors->first('yes_content') }}
										</span>
									</div>
									
								</div>
							</div>
							<div class="col-sm-6 col-xs-6 right">
								<div class="arrow_line"><span></span></div>
								<label class="label_title">If No</label>
								<div class="row_inp_rh">
									<div class="form-group @if($errors->first('frequancy_neagtive')) has-error @endif">
										<label class="form_title form_title_reminder" class="site_name">Send reminder e-mail Frequancy <span aria-required="true" class="required"> * </span></label>
										@if(isset($workflow->varFrequancyNegative))
										@php $selected = $workflow->varFrequancyNegative; @endphp
										@elseif(null !== old('frequancy_neagtive'))
										@php $selected = old('frequancy_neagtive'); @endphp
										@else
										@php $selected = ''; @endphp
										@endif
										<select id="frequancy_neagtive" name="frequancy_neagtive" data-sort data-order class="form-control bs-select select2 status_select">
											<option value="" @if($selected == "") selected @endif>Select frequancy</option>
											<option value="1" @if($selected == "1") selected @endif>Everyday</option>
											<option value="3" @if($selected == "3") selected @endif>Every three days</option>
											<option value="4" @if($selected == "4") selected @endif>Every four days</option>
										</select>
										<span class="help-block">
											{{ $errors->first('frequancy_neagtive') }}
										</span>
									</div>
									<div class="form-group @if($errors->first('no_content')) has-error @endif">
										<label class="form_title" for="no_content">Email Content<span aria-required="true" class="required"> * </span></label>
										{!! Form::textarea('no_content',isset($workflow->txtFrequancyNegative)?$workflow->txtFrequancyNegative:old('yes_content'),array('class' => 'form-control','palceholder'=>'No email content','style'=>'max-height:80px;')) !!}
										<span class="help-block">
											{{ $errors->first('no_content') }}
										</span>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
			
			
			
			
			
			
			<div class="clearfix"></div>
			<div class="col-md-12 text-left">
			<h3 class="form-section">{{ trans('workflow::template.common.displayinformation') }}</h3>
			</div>
			
		</div>
	</div>
	<div class="row">
		<div class="col-md-6">
			@include('powerpanel.partials.displayInfo',['display' => isset($workflow->chrPublish)?$workflow->chrPublish:'Y'])
		</div>
	</div>
</div>
<div class="form-actions">
	<div class="row">
		<div class="col-md-12">
			<button type="submit" name="saveandedit" class="btn btn-green-drake" value="saveandedit">{!! trans('workflow::template.common.saveandedit') !!}</button>
			<button type="submit" name="saveandexit" class="btn btn-green-drake" value="saveandexit">{!! trans('workflow::template.common.saveandexit') !!}</button>
			<a class="btn btn-outline red" href="{{ url('powerpanel/workflow') }}">{{ trans('workflow::template.common.cancel') }}</a>
		</div>
	</div>
</div>
{!! Form::close() !!}

@section('scripts')
<script type="text/javascript">
window.site_url =  '{!! url("/") !!}';
var seoFormId = 'frmWorkflow';
var user_action = "{{ isset($workflow)?'edit':'add' }}";
var moduleAlias = 'workflow';
	var categoryAllowed = false;
</script>
<script src="{{ $CDN_PATH.'resources/global/plugins/bootstrap-fileinput/bootstrap-fileinput.js' }}" type="text/javascript"></script>
<script src="{{ $CDN_PATH.'resources/pages/scripts/custom.js' }}" type="text/javascript"></script>
<!-- BEGIN CORE PLUGINS -->
<script src="{{ $CDN_PATH.'resources/global/plugins/bootstrap/js/bootstrap.min.js' }}" type="text/javascript"></script>
<script src="{{ $CDN_PATH.'resources/global/plugins/bootstrap-hover-dropdown/bootstrap-hover-dropdown.min.js' }}" type="text/javascript"></script>
<script src="{{ $CDN_PATH.'resources/global/plugins/jquery-slimscroll/jquery.slimscroll.min.js' }}" type="text/javascript"></script>
<!-- END CORE PLUGINS -->
<!-- BEGIN PAGE LEVEL SCRIPTS -->
<script src="{{ $CDN_PATH.'resources/global/plugins/bootstrap-maxlength/bootstrap-maxlength.min.js' }}" type="text/javascript"></script>
<script src="{{ $CDN_PATH.'resources/pages/scripts/packages/workflow/workflow-validations.js' }}" type="text/javascript"></script>

<!-- END PAGE LEVEL SCRIPTS -->
@endsection