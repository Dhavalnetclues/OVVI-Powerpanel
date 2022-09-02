/**
 * This method validates service form fields
 * since   2016-12-24
 * author  NetQuick
 */
 var Validate = function() {
		var handleService = function() {
				 $("#frmService").validate({
					errorElement: 'span', //default input error message container
					errorClass: 'help-block', // default input error message class
					rules: {
						title: {
							required:true,
							noSpace:true
						},
						//img_id:"required",
						short_description:{
							required:true,
							noSpace:true
						},						
						display_order: {
							required: true,
							minStrict: true,
							number: true,
							noSpace: true
						},
						varMetaTitle: {
							required:true,
							noSpace:true
						},
						varMetaKeyword:{
							required:true,
							noSpace:true
						},
						varMetaDescription:{
							required:true,
							noSpace:true
						},
						'new-alias':{
							specialCharacterCheck:true,
						},
					},
					messages: {
						title: Lang.get('validation.required', { attribute: Lang.get('template.title') }),
						short_description: Lang.get('validation.required', { attribute: Lang.get('template.shortdescription') }),
						display_order: { required: Lang.get('validation.required', { attribute: Lang.get('template.displayorder') }) },
						varMetaTitle: Lang.get('validation.required', { attribute: Lang.get('template.metatitle') }),
						varMetaKeyword: Lang.get('validation.required', { attribute: Lang.get('template.metakeyword') }),
						varMetaDescription: Lang.get('validation.required', { attribute: Lang.get('template.metadescription') })
					},
					errorPlacement: function (error, element) { if (element.parent('.input-group').length) { error.insertAfter(element.parent()); } else if (element.hasClass('select2')) { error.insertAfter(element.next('span')); } else { error.insertAfter(element); } },
					invalidHandler: function(event, validator) { //display error alert on form submit 
								var errors = validator.numberOfInvalids();
								if (errors) {
									$.loader.close(true);
								}  
								$('.alert-danger', $('#frmService')).show();
					},
					highlight: function(element) { // hightlight error inputs
								$(element).closest('.form-group').addClass('has-error'); // set error class to the control group
						},
					unhighlight: function(element) {
								$(element).closest('.form-group').removeClass('has-error'); // set error class to the control group
						},
					submitHandler: function (form) {
						$('body').loader(loaderConfig);
						form.submit();
						$("button[type='submit']").attr('disabled','disabled');
						return false;
					}
				});
				$('#frmService input').on('keypress',function(e) {
						if (e.which == 13) {
								if ($('#frmService').validate().form()) {
										$('#frmService').submit(); //form validation success, call ajax form submit
										$("button[type='submit']").attr('disabled','disabled');
								}
								return false;
						}
				});
		}	 
		return {
				//main function to initiate the module
				init: function() {
						handleService();
				}
		};
}();

jQuery(document).ready(function() 
{   	 
	 Validate.init();
	 jQuery.validator.addMethod("noSpace", function(value, element){
		if(value.trim().length <= 0){
			return false; 	
		}else{
			return true; 	
		}
	}, "This field is required");
});
jQuery.validator.addMethod("phoneFormat", function(value, element) {
	// allow any non-whitespace characters as the host part
	return this.optional( element ) || /((\(\d{3}\) ?)|(\d{3}-))?\d{3}-\d{4}/.test( value );
}, 'Please enter a valid phone number.');

jQuery.validator.addMethod("minStrict", function(value, element) {
	// allow any non-whitespace characters as the host part
	if(value>0){
		return true;
	}else{
		return false;
	}
}, 'Display order must be a number higher than zero');
$('input[type=text]').on('change',function(){
	var input = $(this).val();
	var trim_input = input.trim();
	if(trim_input) {
		$(this).val(trim_input);
		return true;
	}
});

/*********** Remove Image code start Here  *************/
	$(document).ready(function() {
		if($("input[name='img_id']").val() == ''){  
					$('.removeimg').hide();
					$('.image_thumb .overflow_layer').css('display','none');
			 }else{
				 $('.removeimg').show();
					$('.image_thumb .overflow_layer').css('display','block');
			 }

		 $(document).on('click', '.removeimg', function(e) 
		 {    	 	
			$("input[name='img_id']").val('');
			$("input[name='image_url']").val('');
			$(".fileinput-new div img").attr("src",site_url+ "/resources/images/upload_file.gif");

			if($("input[name='img_id']").val() == ''){  
					$('.removeimg').hide();
					$('.image_thumb .overflow_layer').css('display','none');
				}else{
				 $('.removeimg').show();
					$('.image_thumb .overflow_layer').css('display','block');
				}			 
		});
});
/************** Remove Images Code end ****************/