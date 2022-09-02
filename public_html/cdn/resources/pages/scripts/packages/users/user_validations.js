/**
 * This method validates user form fields
 * since   2016-12-24
 * author  NetQuick
 */
var Validate = function () {

		if (userAction == 'edit') {
				var isRequired = false;
				var isPasswordrule = false;
				var isMinlength = false;
				var isMaxlength = false;
				var isEqualTo = false;
		} else {
				var isRequired = true;
				var isPasswordrule = true;
				var isMinlength = 6;
				var isMaxlength = 20;
				var isEqualTo = "#password";
		}
		var handleUsers = function () {
				$("#frmUsers").validate({
						errorElement: 'span', //default input error message container
						errorClass: 'help-block', // default input error message class
						rules: {
								name: {
										required: true,
										noSpace: true,
										xssProtection: true,
										no_url: true
								},
								email: {
										required: true,
										emailFormat: true,
										noSpace: true,
										xssProtection: true,
										no_url: true
								},
								password: {
										required: isRequired,
										passwordrules: isPasswordrule,
										minlength: isMinlength,
										maxlength: isMaxlength,
																																							
								},
								'confirm-password': {
										required: isRequired,                   
										minlength: isMinlength,
										maxlength: isMaxlength,
										equalTo: isEqualTo,
																																								
								},
								'roles[]': {required: true}
						},
						messages: {
								name:{required: Lang.get('validation.required', {attribute: Lang.get('template.userModule.userName')})},
								email:{required: Lang.get('validation.required', {attribute: Lang.get('template.userModule.userEmail')})},
								password: {
										required: Lang.get('validation.required', {attribute: Lang.get('template.userModule.userPassword')}),
										passwordrules: 'Please follow rules for password.',
										minlength:"Password must be at least 6 characters.",									
								},
								'confirm-password': {
										required: Lang.get('validation.required', {attribute: Lang.get('template.userModule.confirmPassord')}),
										passwordrules: 'Please follow rules for password.',
										equalTo: 'Password and confirm password should match',
										minlength:"Password must be at least 6 characters.",
								},
								'roles[]': Lang.get('validation.required', {attribute: Lang.get('template.userModule.userRole')}),
						},
						errorPlacement: function (error, element) {
								if (element.parent('.input-group').length) {
										error.insertAfter(element.parent());
								} else if (element.hasClass('select2')) {
										error.insertAfter(element.next('span'));
								} else {
										error.insertAfter(element);
								}
						},
						invalidHandler: function (event, validator) { //display error alert on form submit 
								var errors = validator.numberOfInvalids();
								if (errors) {
										$.loader.close(true);
								}
								$('.alert-danger', $('#frmUsers')).show();
						},
						highlight: function (element) { // hightlight error inputs
								$(element).closest('.form-group').addClass('has-error'); // set error class to the control group
						},
						unhighlight: function (element) {
								$(element).closest('.form-group').removeClass('has-error'); // set error class to the control group
						},
						submitHandler: function (form) {
								$('body').loader(loaderConfig);
								form.submit();
								return false;
						}
				});
				$('#frmUsers input').keypress(function (e) {
						if (e.which == 13) {
								if ($('#frmUsers').validate().form()) {
										$('#frmUsers').submit(); //form validation success, call ajax form submit
								}
								return false;
						}
				});
		}
		return {
				//main function to initiate the module
				init: function () {
						handleUsers();
				}
		};
}();
jQuery(document).ready(function () {
		Validate.init();
		jQuery.validator.addMethod("noSpace", function (value, element) {
				if (value.trim().length <= 0) {
						return false;
				} else {
						return true;
				}
		}, "This field is required");
});
jQuery.validator.addMethod("phoneFormat", function (value, element) {
		// allow any non-whitespace characters as the host part
		return this.optional(element) || /((\(\d{3}\) ?)|(\d{3}-))?\d{3}-\d{4}/.test(value);
}, 'Please enter a valid phone number.');
jQuery.validator.addMethod("emailFormat", function (value, element) {
		// allow any non-whitespace characters as the host part
		return this.optional(element) || /^[_A-Za-z0-9-]+(\.[_A-Za-z0-9-]+)*@[A-Za-z0-9-]+(\.[A-Za-z0-9-]+)*(\.[A-Za-z]{2,4})$/.test(value);
}, 'Enter valid email format');
$('input[type=text]').change(function(){
	var input = $(this).val();
	var trim_input = input.trim();
	if(trim_input) {
		$(this).val(trim_input);
		return true;
	}
});