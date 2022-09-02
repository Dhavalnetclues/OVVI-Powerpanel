$(document).ready(function() {
			    $('#team_form').validate({
			        errorElement: 'span',
			        errorClass: 'error',
			        ignore: [],
			        rules: {
			            first_name: {
			                required: true,
			                noSpace: true,
			                maxlength: 600,
			                check_special_char: true,
			                no_url: true,
			            },
			            phone_no: {
			                maxlength: 20,
			                digits: true,
			                checkallzero: {
			                    depends: function() {
			                        if ($("#phone_no").val() != '') {
			                            return true;
			                        } else {
			                            return false;
			                        }
			                    }
			                },
			                phonenumberFormat: {
			                    depends: function() {
			                        if ($("#phone_no").val() != '') {
			                            return true;
			                        } else {
			                            return false;
			                        }
			                    }
			                },
			            },
			            contact_email: {
			                required: true,
			                maxlength: 100,
			                emailFormat: true,
			            },
			            user_message: {
			                maxlength: 600,
			                no_url: true,
			                check_special_char: true,
			            },
			            ct_hiddenRecaptcha: {
			                required: true
			            }
			        },
			        messages: {
			            first_name: {
			                required: "Name is required.",
			                maxlength: "You reach the maximum limit {0}.",
			            },
			            phone_no: {
                            maxlength: "You reach the maximum limit {0}.",
                            digits: "Only number.",
                        },
			            contact_email: {
			                required: "Email is required.",
			                maxlength: "You reach the maximum limit {0}.",
			            },
			            user_message: {
			                maxlength: "You reach the maximum limit {0}.",
			            },
			            ct_hiddenRecaptcha: {
			                required: "Captcha is required.",
			            },
			        },
			        onfocusout: function(element) {
			            this.element(element);
			        },
			        errorPlacement: function(error, element) {
			            if (element.attr('id') == 'g-recaptcha-response') {
			                error.insertAfter(element.parent());
			            } else {
			                error.insertAfter(element);
			            }
			        },
			    });
			});