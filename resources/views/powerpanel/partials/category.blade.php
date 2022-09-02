@if(isset($categories))
<style type="text/css">
	.select2-results__option[role=group] ul {
    padding: 0px 0 0px 20px !important;
    font-size: 13px !important;
	}
</style>
@if(isset($required))
	@if($required)
		@php $required=true; @endphp
	@else
		@php $required=false; @endphp
	@endif
@else
	@php $required=false; @endphp
@endif
@if(isset($listing))
	@if($listing)
		@php $listing=true; @endphp
	@else
		@php $listing=false; @endphp
	@endif
@else
	@php $listing=false; @endphp
@endif

@if(isset($highlight))	
	@php $highlight=$highlight; @endphp
@else
	@php $highlight=''; @endphp
@endif

@if(isset($multiple))	
	@php $multiple=$multiple; @endphp
@else
	@php $multiple=true; @endphp
@endif
<div class="row">
	<div @if(!$listing) class="col-md-12" @endif>
		<div class="form-group  @if($errors->first('category_id')) has-error @endif">
			@if(!$listing)<label class="form_title {{ $highlight }}" for="category_id">{{ trans('template.common.selectcategory') }}@if($required)<span aria-required="true" class="required"> * </span>@endif</label>@endif
			<select id="category_id" class="form-control" name="category_id[]" @if(!$listing) @if($multiple) multiple @endif @endif>				
			</select>
			@if(null!==$errors->first('category_id'))
			<span class="help-block">
				{{ $errors->first('category_id') }}
			</span>
			@endif
		</div>
	</div>
</div>

@section('cat_select2_config')
<script type="text/javascript">
$.fn.select2.amd.define('select2/data/customAdapter', ['select2/data/array', 'select2/utils'],
    function (ArrayAdapter, Utils) {
        function CustomDataAdapter ($element, options) {
 	        CustomDataAdapter.__super__.constructor.call(this, $element, options);
        }
        Utils.Extend(CustomDataAdapter, ArrayAdapter);
				CustomDataAdapter.prototype.updateOptions = function (data) {
						this.$element.html('');            
            this.addOptions(this.convertToOptions(data));
        }        
        return CustomDataAdapter;
    }
);
var customAdapter = $.fn.select2.amd.require('select2/data/customAdapter');
var selectedMenu='';
	@php
	$selectedMenu1='';
	if(null !== app('request')->input('category')){
	  $selectedMenu1=app('request')->input('category');
	}else
	{
		if(isset($singleCat) && $singleCat==true){
			$selectedMenu1 = json_encode( isset($data) && $data->txtCategories!=null ? $data->txtCategories : old('category_id') );	
		}else{
			$selectedMenu1 = json_encode( isset($data) && $data->txtCategories!=null ? unserialize($data->txtCategories) : old('category_id') );	
		}
		
	}
	//$selectedMenu = ($data->txtCategories == null)?[]:$unserialized;
	@endphp
var selectedMenu={!! $selectedMenu1 !!};
@if(Auth::user()->hasRole('user_account'))
	var cat = $.parseJSON('{!! $categories !!}'=='false'?'[{"id":"","text":"No Category available"}]':'{!! $categories !!}');
@else
	var cat = $.parseJSON('{!! $categories !!}'=='false'?'[{"id":"addCat","text":"Add Category"}]':'{!! $categories !!}');
@endif

$(document).ready(function() {
		initSelect2(cat, selectedMenu);		
});
function initSelect2(cat, selectedMenu){
	$.when(   	
   	$("#category_id").select2({				
				placeholder: "{{ trans('template.common.selectcategory') }}",
				dataAdapter: customAdapter,
				data: cat,
				minimumResultsForSearch: 10
		}).on("select2:opening select2:closing", function (e) { 
			//$('.select2-dropdown li[role=group] strong').hide();			
		}).on("change.select2", function(e){
			setSelectedOptions(cat);
		})
	).done(function(){
	  $("#category_id").select2('val', selectedMenu);
	});	
}

function setSelectedOptions(cat){
	var rootKey;
		var childParentArr=[];
		for(var prop in cat) {		 
		 rootKey = prop;
		 for( var key in cat[rootKey] ) {
			 if(key=='children'){
			 	var obj = cat[rootKey][key];
			 	 $.each(obj, function (i, item) {			 	 	
			 	 	childParentArr[item.text] = item.parentTitle;
			 	 });
			 }
			}
		}
		$('.select2-selection__choice').each(function(){
			var title = $(this).attr('title');			
			if(childParentArr[title]!==undefined){
				var a=$(this).first().contents().filter(function() {
    			return this.nodeType == 3;
				}).replaceWith(childParentArr[title]+'<i class="fa fa-angle-double-right" aria-hidden="true"></i>'+title);				
			}
		});		
}
//Add category dynamically======================================
$('#category_id').change(function(){	
	$($(this).children('option:checked')).each(function(){			
			if($(this).val()=='addCat'){
				$(this).removeAttr('selected');				
				showAddCat();				
			}
		});
	$('#addCatModal input').val(null);	
});
function showAddCat(){
	$('#addCatModal').modal({
			backdrop: 'static',
			keyboard: false
	});	
}
$('#addCat').click( function() {
		var title = $.trim($('#addCatModal input[name=title]').val());
		var parentCategory = $('#parent_category_id').val();
		if(parentCategory==""){
				parentCategory = 0;
		}
		if(title.length<1 || title==''){
			$('#addCatModal #catErr').removeClass('hide');
		}else{
			$('#addCatModal #catErr').addClass('hide');
			jQuery.ajax({
					type: "POST",
					url: window.site_url+'/powerpanel/'+$(this).data('module')+'/ajaxCatAdd',
					data: {						
							"varTitle": $('#addCatModal input[name=title]').val(),
							"selectedCat[]": $('#category_id').val(),
							"parent_category_id":parentCategory,
					},
					dataType:'json',
					async: false,
					success: function(result) {
						$('#addCatModal').modal('hide');						
						$('#category_id').data('select2').dataAdapter.updateOptions([null]);
						$('#category_id').data('select2').dataAdapter.updateOptions($.parseJSON(result.cat));						
						$("#category_id").select2('val', result.selected);
						$( "#parent_category_id" ).replaceWith( result.categoriesHtml );
					}
			});
		}
});
</script>
@endsection
@endif