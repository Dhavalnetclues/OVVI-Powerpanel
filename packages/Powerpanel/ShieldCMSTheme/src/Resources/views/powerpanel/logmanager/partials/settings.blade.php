<div class="setting-notify"></div>
<div class="col-md-12">	
	<h4 class="form-section">Image size</h4>
	<div class="col-md-6">
		
		<div class="form-group form-md-line-input">
			{!! Form::text('height' , null, array('class' => 'form-control height', 'autocomplete'=>"off")) !!}
			<label class="form_title" for="height"> Height <span aria-required="true" class="required"> * </span></label>
			<span class="help-block">
				{{ $errors->first('height') }}
			</span>
		</div>
	</div>	
	<div class="col-md-6">				
		<div class="form-group form-md-line-input">
			{!! Form::text('width' , null, array('class' => 'form-control width', 'autocomplete'=>"off")) !!}
			<label class="form_title" for="width"> Width <span aria-required="true" class="required"> * </span></label>
			<span class="help-block">
				{{ $errors->first('width') }}
			</span>
		</div>
	</div>
</div>