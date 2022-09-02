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


<div class="col-md-12">

<div class="col-md-6">
	<h4 class="form-section">Short Description Length	</h4>
	<div class="form-group form-md-line-input">
		{!! Form::text('short_desc_length' , null, array('class' => 'form-control short_desc_length', 'autocomplete'=>"off")) !!}
		<label class="form_title" for="short_desc_length"> Length(Number of characters) <span aria-required="true" class="required"> * </span></label>
		<span class="help-block">
			{{ $errors->first('short_desc_length') }}
		</span>
	</div>
</div>
</div>