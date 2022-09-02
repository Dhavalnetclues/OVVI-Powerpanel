 var Validate = function() {
                var handleService = function() {
                                 $(".login-form").validate({
                                        errorElement: 'span', //default input error message container
                                        errorClass: 'help-block', // default input error message class
                                        ignore: [],
                                        rules: {
                                                email: {
                                                        required: true
                                                },
                                                password: {
                                                        required: true
                                                },
                                                remember: {
                                                        required: false
                                                }
                                        },
                                        messages: {
                                                email: {
                                                    required: "Email is required."
                                                },
                                                password: {
                                                    required: "Password is required."
                                            }
                                        },
                                        invalidHandler: function(event, validator) { //display error alert on form submit   
                                                                $('.alert-danger', $('.login-form')).show();
                                                },
                                        highlight: function(element) { // hightlight error inputs
                                                                $(element).closest('.form-group').addClass('has-error'); // set error class to the control group
                                                },
                                        unhighlight: function(element) {
                                                $(element).closest('.form-group').removeClass('has-error'); // set error class to the control group
                                        },
                                        submitHandler: function (form) {
                                            $("#login_submit").attr("disabled","disabled");
                                                form.submit();
                                                return false;
                                        }
                                });
                                $('.login-form input').keypress(function(e) {
                                                if (e.which == 13) {
                                                                if ($('.login-form').validate().form()) {
                                                                    $("#login_submit").attr("disabled","disabled");
                                                                                $('.login-form').submit(); //form validation success, call ajax form submit
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