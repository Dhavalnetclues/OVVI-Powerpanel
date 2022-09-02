/**
* This method validates banner form fields
* since   2016-12-24
* author  NetQuick
*/
var Validate = function() {
	var handlePassword = function() {
		$("#changePassword").validate({
			errorElement: 'span', //default input error message container
			errorClass: 'help-block', // default input error message class
			rules: {
			  old_password: "required",
			  new_password: {
			  		required:true,
			  		passwordrules:true,
			  		minlength:6,
			  		maxlength:20
			  },
			  confirm_password: {
			  	required: true,
			  	passwordrules:true,
		  		minlength:6,
		  		maxlength:20,
    			equalTo: "#newpassword"
      	}
			},
			messages: {
			  old_password: {
			  	required : Lang.get('validation.required', { attribute:'Old Password' })
			  },
			  new_password: {
          required: Lang.get('validation.required', { attribute:'New Password' }),
          passwordrules: 'Please follow rules for password.'
        },
        confirm_password: {
        	required: Lang.get('validation.required', { attribute:'Confirm Password' }),
        	passwordrules: 'Please follow rules for password.',
        	equalTo: "Confirm Password and New Password must match."
        }
			},
			errorPlacement: function (error, element) { if (element.parent('.input-group').length) { error.insertAfter(element.parent()); } else if (element.hasClass('select2')) { error.insertAfter(element.next('span')); } else { error.insertAfter(element); } },
			invalidHandler: function(event, validator) { //display error alert on form submit
				var errors = validator.numberOfInvalids();
		    if (errors) {
		    	$.loader.close(true);
		    }   
				$('.alert-danger', $('#changePassword')).show();
			},
			highlight: function(element) { // hightlight error inputs
				$(element).closest('.form-group').addClass('has-error'); // set error class to the control group
			},
			submitHandler: function (form) { // for demo
				$('body').loader(loaderConfig);
			  form.submit();
			  return false;
			}
		});
		$('#changePassword input').keypress(function(e) {
			if (e.which == 13) {
				if ($('#changePassword').validate().form()) {
					$('#changePassword').submit(); //form validation success, call ajax form submit
				}
				return false;
			}
		});
	}
	return {
		//main function to initiate the module
		init: function() {
			handlePassword();
		}
	};
}();
jQuery(document).ready(function() {   
	Validate.init();
});