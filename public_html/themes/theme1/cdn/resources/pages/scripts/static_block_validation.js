var Static_block = function() {
	var handleLogin = function() {
		$('.static_block_form').validate({
			errorElement: 'span', //default input error message container
			errorClass: 'help-block', // default input error message class			
			rules: {
				title: {
					required:true,
					noSpace:true
				},
			},
			messages: {
				title: Lang.get('validation.required', { attribute: Lang.get('template.title') }),
			},

			errorPlacement: function (error, element) { if (element.parent('.input-group').length) { error.insertAfter(element.parent()); } else if (element.hasClass('select2')) { error.insertAfter(element.next('span')); } else { error.insertAfter(element); } },
			invalidHandler: function(event, validator) { //display error alert on form submit
					var errors = validator.numberOfInvalids();
			    if (errors) {
			    	$.loader.close(true);
			    } 
					$('.alert-danger', $('.static_block_form')).show();
				},
			highlight: function(element) { // hightlight error inputs
					$(element).closest('.form-group').addClass('has-error'); // set error class to the control group
				},
			unhighlight: function(element) {
					$(element).closest('.form-group').removeClass('has-error'); // set error class to the control group
				},
			submitHandler: function(form) {
				$('body').loader(loaderConfig);
				form.submit(); // form validation success, call ajax form submit
			}
		});
		$('.form_control input').keypress(function(e) {
			if (e.which == 13) {
				if ($('.form_control').validate().form()) {
					$('.form_control').submit(); //form validation success, call ajax form submit
				}
				return false;
			}
		});
	}
	return {
		//main function to initiate the module
		init: function() {
			handleLogin();
		}
	};
}();
jQuery(document).ready(function() {
	Static_block.init();
	jQuery.validator.addMethod("noSpace", function(value, element){
		if(value.trim().length <= 0){
			return false; 	
		}else{
			return true; 	
		}
	}, "This field is required");
});
$('input[name=title]').change(function(){
	var title = $(this).val();
  var trim_title = title.trim();
  if(trim_title) {
  	$(this).val(trim_title);
  	return true;
 	}
});