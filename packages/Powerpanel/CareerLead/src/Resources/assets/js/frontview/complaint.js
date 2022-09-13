$(document).ready(function() {
    $('#complaint_page_form').validate({
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
                badwordcheck: true,
            },
            phone_number: {
                maxlength: 20,
                digits: true,
                checkallzero: {
                    depends: function() {
                        if ($("#phone_number").val() != '') {
                            return true;
                        } else {
                            return false;
                        }
                    }
                },
                phonenumberFormat: {
                    depends: function() {
                        if ($("#phone_number").val() != '') {
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
                badwordcheck: true
            },
            'g-recaptcha-response': {
                required: true
            }
        },
        messages: {
            first_name: {
                required: "This is required.",
                maxlength: "You reach the maximum limit {0}.",
            },
            last_name: {
                maxlength: "You reach the maximum limit {0}.",
            },
            phone_number: {
                maxlength: "You reach the maximum limit {0}.",
                digits: "Only number.",
            },
            contact_email: {
                required: "This is required.",
                maxlength: "You reach the maximum limit {0}.",
            },
            user_message: {
                maxlength: "You reach the maximum limit {0}.",
            },
            'g-recaptcha-response': {
                required: "Please select I'm not a robot.",
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
    $("#complaint_page_form").trigger("reset");
});