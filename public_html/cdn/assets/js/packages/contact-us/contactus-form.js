function KeycheckOnlyPhonenumber(e) {
    var t = 0;
    t = document.all ? 3 : document.getElementById ? 1 : document.layers ? 2 : 0;
    if (document.all)
        e = window.event;
    var n = "";
    var r = "";
    if (t == 2) {
        if (e.which > 0)
            n = "(" + String.fromCharCode(e.which) + ")";
        r = e.which
    } else {
        if (t == 3) {
            r = window.event ? event.keyCode : e.which
        } else {
            if (e.charCode > 0)
                n = "(" + String.fromCharCode(e.charCode) + ")";
            r = e.charCode
        }
    }
    if (r >= 65 && r <= 90 || r >= 97 && r <= 122 || r >= 33 && r <= 39 || r >= 42 && r <= 42 || r >= 44 && r <= 44 || r >= 46 && r <= 47 || r >= 58 && r <= 64 || r >= 91 && r <= 96 || r >= 123 && r <= 126) {
        return false
    }
    return true
}


var Validate = function() {
    var handleContact = function() {
        $("#contactus_form").validate({
            errorElement: 'span', //default input error message container
            errorClass: 'help-block error', // default input error message class
            ignore: [],
            rules: {
                first_name: {
                    required: true,
                    noSpace: true,
                    alpha: true,
                    xssProtection: true,
                    check_special_char: true,
                    no_url: true,
                    badwordcheck: true
                },
                phone: {
                    required: true,
                    minlength: 6,
                    maxlength: 20,
                    phonenumber: {
                        depends: function() {
                            if (($("#phone").val()) != '') {
                                return true;
                            } else {
                                return false;
                            }
                        }
                    }
                },
                "g-recaptcha-response": {
                    required: true
                },
                email: {
                    required: true,
                    emailFormat: true,
                    badwordcheck: true
                },
                message: {
                    required: true,
                    badwordcheck: true,
                    messageValidation: true,
                    xssProtection: true,
                    no_url: true
                }
            },
            messages: {
                first_name: {
                    required: "Full name field is required.",
                },
                phone: {
                    required: "Phone field is required.",
                },
                message: {
                    required: "Message field is required.",
                },
                email: {
                    required: "Email field is required.",
                },
                "g-recaptcha-response": {
                    required: "Captcha is required.",
                },
            },
            errorPlacement: function(error, element) {
                if (element.attr('id') == 'g-recaptcha-response-1') {
                    error.insertAfter(element.parent());
                } else if (element.attr("name") == "category") {
                    error.insertAfter(element.parent());
                } else {
                    error.insertAfter(element);
                }
            },
            invalidHandler: function(event, validator) { //display error alert on form submit
                $('.alert-danger', $('#contactus_form')).show();
            },
            highlight: function(element) { // hightlight error inputs
                $(element).closest('.form-group').addClass('has-error'); // set error class to the control group
            },
            unhighlight: function(element) {
                $(element).closest('.form-group').removeClass('has-error'); // set error class to the control group
            },
            submitHandler: function(form) {
                //                grecaptcha.execute();
                $("#contact_submit").attr("disabled", "disabled");
                form.submit();
                return false;
            }
        });
        $('#contactus_form input').keypress(function(e) {
            if (e.which == 13) {
                if ($('#contactus_form').validate().form()) {
                    $("#contact_submit").attr("disabled", "disabled");
                    $('#contactus_form').submit(); //form validation success, call ajax form submit
                }
                return false;
            }
        });
    }
    return {
        //main function to initiate the module
        init: function() {
            handleContact();
        }
    };
}();

function hiddenCallBack() {
    document.getElementById("cont").submit();
}

jQuery(document).ready(function() {
    Validate.init();
    $("#contactus_form").trigger("reset");
    //    $(':input', '#contactus_form')
    //            .not(':button, :submit, :reset, :hidden')
    //            .val('')
    //            .prop('checked', false)
    //            .prop('selected', false);


    $.validator.addMethod("phonenumber", function(value, element) {
        var numberPattern = /\d+/g;
        var newVal = value.replace(/\D/g);
        if (parseInt(newVal) <= 0) {
            return false;
        } else {
            return true;
        }
    }, 'Please enter a valid phone number.');

    var blacklist = /\b(nude|naked|sex|porn|porno|sperm|fuck|penis|pussy|vagina|boobs|asshole|shit|bitch|motherfucker|dick|orgasm|fucker)\b/; /* many more banned words... */
    $.validator.addMethod("badwordcheck", function(value) {
        return !blacklist.test(value.toLowerCase());
    }, "Please remove bad word/inappropriate language.");
    $.validator.addMethod("minimum_length", function(value, element) {
        if ($("#phone_no").val().length < 5 || $("#phone_no").val().length > 20) {
            return false;
        } else {
            return false;
        }
    }, 'Please enter a phone number minimum 5 digits and maximum 20 digits.');
    jQuery.validator.addMethod("noSpace", function(value, element) {
        if (value.trim().length <= 0) {
            return false;
        } else {
            return true;
        }
    }, "No space please and don't leave it empty");
    jQuery.validator.addMethod("emailFormat", function(value, element) {
        // allow any non-whitespace characters as the host part
        return this.optional(element) || /^[_A-Za-z0-9-]+(\.[_A-Za-z0-9-]+)*@[A-Za-z0-9-]+(\.[A-Za-z0-9-]+)*(\.[A-Za-z]{2,4})$/.test(value);
    }, 'Enter valid email format');
    jQuery.validator.addMethod("messageValidation", function(value, element) {
        // allow any non-whitespace characters as the host part
        return this.optional(element) || /<(\w+)((?:\s+\w+(?:\s*=\s*(?:(?:"[^"]*")|(?:'[^']*')|[^>\s]+))?)*)\s*(\/?)>/.test(value) == false ? true : false;
    }, 'Enter valid message');
    jQuery.validator.addMethod("xssProtection", function(value, element) {
        // allow any non-whitespace characters as the host part
        return this.optional(element) || /<(\w+)((?:\s+\w+(?:\s*=\s*(?:(?:"[^"]*")|(?:'[^']*')|[^>\s]+))?)*)\s*(\/?)>/.test(value) == false ? true : false;
    }, 'Enter valid input');
    $.validator.addMethod("check_special_char", function(value, element) {
        if (value != '') {
            if (value.match(/^[\x20-\x7E\n]+$/)) {
                return true;
            } else {
                return false;
            }
        } else {
            return true;
        }
    }, 'Please enter valid input');
    $.validator.addMethod("alpha", function(value, element) {
        return this.optional(element) || value == value.match(/^[a-zA-Z\s]+$/);
    }, 'Please enter valid input');
    $.validator.addMethod('no_url', function(value, element) {
        var re = /^[a-zA-Z0-9\-\.\:\\]+\.(com|org|net|mil|edu|COM|ORG|NET|MIL|EDU)$/;
        var re1 = /(ftp|http|https):\/\/(\w+:{0,1}\w*@)?(\S+)(:[0-9]+)?(\/|\/([\w#!:.?+=&%@!\-\/]))?/;
        var trimmed = $.trim(value);
        if (trimmed == '') {
            return true;
        }
        if (trimmed.match(re) == null && re1.test(trimmed) == false) {
            return true;
        }
    }, "URL doesn't allowed");
    $('input[name=email]').change(function() {
        var email = $(this).val();
        var trim_email = email.trim();
        if (trim_email) {
            $(this).val(trim_email);
            return true;
        }
    });
});
//====================================================================