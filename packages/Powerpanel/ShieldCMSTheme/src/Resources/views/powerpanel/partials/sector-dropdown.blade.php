<div class="form-md-line-input cm-floating">
	<label class="form-label {{ $Class_varSector }}" for="varSector">Select Sector Type <span aria-required="true" class="required"> * </span> </label>
	@if(isset($disable) && !empty($disable))

	@php $selectdisable = $disable; @endphp

	@else
	@php $selectdisable = '';@endphp
	@endif
	<select class="form-control" name="sector" id="varSector" {{$selectdisable}} data-choices>
		<option value="">Select Sector Type</option>
		@if(!empty($sectorList))
			@foreach($sectorList as $key => $ValueSector)
				@php $selected = ''; @endphp
				@if($key == $selected_sector)
					@php $selected = 'selected'; @endphp
				@endif
				<option value="{{ $key }}" {{ $selected }}>{{ $ValueSector }}</option>
			@endforeach
		@endif
	</select>
	<span class="help-block">{{ $errors->first('sector') }}</span>
</div>
