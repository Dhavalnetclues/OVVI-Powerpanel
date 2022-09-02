var ValidateSubscribe = function() {
    var handleSubscribe = function() {
        $("#subscribe_form").validate({
            errorElement: 'span', //default input error message container
            errorClass: 'help-block', // default input error message class
            ignore: [],
            rules: {
                "g-recaptcha-response": {
                    required: true
                },
                email: {
                    required: true,
                    emailFormat: true
                }
            },
            messages: {
                email: {
                    required: "Email is required"
                },
                "g-recaptcha-response": {
                    required: "Captcha is required"
                }
            },
            errorPlacement: function(error, element) {
                if (element.attr('id') == 'g-recaptcha-response') {
                    error.insertAfter(element.parent().parent());
                } else {
                    error.insertAfter(element);
                }
            },
            invalidHandler: function(event, validator) { //display error alert on form submit   
                $('.alert-danger', $('#subscribe_form')).show();
            },
            highlight: function(element) { // hightlight error inputs
                $(element).closest('.form-group').addClass('has-error'); // set error class to the control group
            },
            unhighlight: function(element) {
                $(element).closest('.form-group').removeClass('has-error'); // set error class to the control group
            },
            submitHandler: function(form) {
                $.ajax({
                    url: form.action,
                    type: form.method,
                    dataType:'JSON',
                    data: $(form).serialize(),
                    success: function(response) {
                    	var element = $("#subscribe_form input[type=email]");
                    	if(typeof response.success != "undefined"){
                    		$(element).closest('.form-group').removeClass('has-error');
                    		$("#subscribe_form input[type=email]").val(null);
                        console.log(response.success)
                    	}else if(typeof response.error != "undefined"){                    		
                    		var error = '<span id="email-error" class="help-block">'+response.error+'</span>';
                    		$(error).insertAfter(element);
                    		$(element).closest('.form-group').addClass('has-error');                    		
                    	}                    	
                    },
                    complete:function(){
                    	grecaptcha.reset();
                    }
                });

            }
        });
        $('#subscribe_form input').keypress(function(e) {
            if (e.which == 13) {
                if ($('#subscribe_form').validate().form()) {
                    alert(3)
                }
                return false;
            }
        });
    }
    return {
        //main function to initiate the module
        init: function() {
            handleSubscribe();
        }
    };
}();

$.validator.addMethod("emailFormat", function(value, element) {
    // allow any non-whitespace characters as the host part
    return this.optional(element) || /^[_A-Za-z0-9-]+(\.[_A-Za-z0-9-]+)*@[A-Za-z0-9-]+(\.[A-Za-z0-9-]+)*(\.[A-Za-z]{2,4})$/.test(value);
}, 'Enter valid email format');


    ValidateSubscribe.init();
