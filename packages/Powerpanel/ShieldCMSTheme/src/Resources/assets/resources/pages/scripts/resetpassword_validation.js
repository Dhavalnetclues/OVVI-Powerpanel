 var Validate = function() {
		var handleService = function() {
				 $(".reset-password-form").validate({
					errorElement: 'span', //default input error message container
					errorClass: 'help-block', // default input error message class
					ignore: [],
					rules: {					
								email: {
										required: true,
										email:true
								},
								password: {
										required: true,
										passwordrules:true,
										minlength:6,
			  						maxlength:20,
								},
								password_confirmation: {
										required: true,
										passwordrules:true,
							  		minlength:6,
							  		maxlength:20,
					    			equalTo: "#password"
								}
					},
					messages: {
							email: {
										required: "Email is required."
								},
								password: {
										required: "Password is required.",
										passwordrules: 'Please follow rules for password.'
								},
								password_confirmation: {
										required: "Confirm password is required.",
										passwordrules: 'Please follow rules for password.'
								}
					},
					invalidHandler: function(event, validator) { //display error alert on form submit   
								$('.alert-danger', $('.reset-password-form')).show();
						},
					highlight: function(element) { // hightlight error inputs
								$(element).closest('.form-group').addClass('has-error'); // set error class to the control group
						},
					unhighlight: function(element) {
        				$(element).closest('.form-group').removeClass('has-error'); // set error class to the control group
    				},
					submitHandler: function (form) {
						form.submit();
						return false;
					}
				});
				$('.reset-password-form input').keypress(function(e) {
						if (e.which == 13) {
								if ($('.reset-password-form').validate().form()) {
										$('.reset-password-form').submit(); //form validation success, call ajax form submit
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