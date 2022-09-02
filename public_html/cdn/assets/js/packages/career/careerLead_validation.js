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

        $("#job_application_form").validate({
            errorElement: 'span', //default input error message container
            errorClass: 'help-block error', // default input error message class
            ignore: [],
            rules: {
                fname: {
                    required: true,
                    noSpace: true,
                    xssProtection: true,
                    check_special_char: true,
                    no_url: true,
                    alpha: true,
                    badwordcheck: true
                },
                lname: {
                    required: true,
                    noSpace: true,
                    xssProtection: true,
                    check_special_char: true,
                    no_url: true,
                    alpha: true,
                    badwordcheck: true
                },
                phoneNo: {
                    required: true,
                    noSpace: true,
                    minlength: 9,
                    maxlength: 14,
                    phonenumber: {
                        depends: function() {
                            if (($("#phoneno").val()) != '') {
                                return true;
                            } else {
                                return false;
                            }
                        }
                    }
                },
                email: {
                    required: true,
                    emailFormat: true,
                    badwordcheck: true
                },
                address1: {
                    required: true,
                    check_special_char: true,
                    noSpace: true,
                    badwordcheck: true,
                    xssProtection: true,
                },
                address2: {
                    check_special_char: true,
                    badwordcheck: true,
                    xssProtection: true,
                },
                country: {
                    required: true,
                    noSpace: true,
                    alpha: true,
                    badwordcheck: true,
                },
                state: {
                    required: true,
                    noSpace: true,
                    badwordcheck: true,
                    alpha: true,
                },
                city: {
                    required: true,
                    noSpace: true,
                    alpha: true,
                    badwordcheck: true,
                },
                postalCode: {
                    required: true,
                    digits: true,
                    minlength: 6,
                    maxlength: 6,
                },
                dob: {
                    required: true,
                },
                resume: {
                    required: true,
                },
                "resume": {
                    required: true,
                    // accept: "application/pdf,application/docx,application/doc,application/zip",
                    CheckEXT: true,
                    // system_file_validation: true,
                    Chk_File_Size: true,
                    minimum_limit: true,
                },
                'g-recaptcha-response': {
                    required: true
                },
                immigrationStatus: {
                    xssProtection: true,
                    check_special_char: true,
                    no_url: true,
                    badwordcheck: true
                },
                jobOpening: {
                    xssProtection: true,
                    check_special_char: true,
                    no_url: true,
                    badwordcheck: true
                },
                describeExp: {
                    xssProtection: true,
                    check_special_char: true,
                    no_url: true,
                    badwordcheck: true
                },
                reasonForChange: {
                    xssProtection: true,
                    check_special_char: true,
                    no_url: true,
                    badwordcheck: true
                },
                whenToStart: {
                    xssProtection: true,
                    check_special_char: true,
                    no_url: true,
                    badwordcheck: true
                }
            },
            messages: {
                fname: {
                    required: "Your first name is required.",
                },
                lname: {
                    required: "Your last name is required.",
                },
                phoneNo: {
                    minlength: "Please enter at least 6 Digits",
                    maxlength: "You reach the maximum limit.",
                },
                email: {
                    required: "Your email address is required.",
                },
                address1: {
                    required: "Your address1 is required.",
                },
                country: {
                    required: "Please Enter Country.",
                },
                state: {
                    required: "Please Enter Sate.",
                },
                city: {
                    required: "Please Enter City.",
                },
                postalCode: {
                    required: "Please Enter PostalCode.",
                },
                dob: {
                    required: "Please Enter Date Of Birth.",
                },
                "resume": {
                    required: "Resume Field is Required.",
                    // accept: "Please Upload valid Documents.",
                    Chk_File_Size: "Please Upload Valid File Size"
                },
                'g-recaptcha-response': {
                    required: "Please select I'm not a robot.",
                }
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
                $('.alert-danger', $('#job_application_form')).show();
            },
            highlight: function(element) { // hightlight error inputs
                $(element).closest('.form-group').addClass('has-error'); // set error class to the control group
            },
            unhighlight: function(element) {
                $(element).closest('.form-group').removeClass('has-error'); // set error class to the control group
            },
            submitHandler: function(form) {
                $("#career_submit").attr("disabled", "disabled");
                form.submit();
                return false;
            }
        });
        $('#job_application_form input').keypress(function(e) {
            if (e.which == 13) {
                if ($('#job_application_form').validate().form()) {
                    $("#career_submit").attr("disabled", "disabled");
                    $('#job_application_form').submit(); //form validation success, call ajax form submit
                }
                return false;
            }
        });
    }
    return {
        //main function to initiate the module
        init: function() {
            handleContact();
            document.getElementById("job_application_form").reset();
        }
    };
}();

function hiddenCallBack() {
    document.getElementById("cont").submit();
}
jQuery(document).ready(function() {
    Validate.init();

    $(':input', '#job_application_form')
        .not(':button, :submit, :reset, :hidden')
        .val('')
        .prop('checked', false)
        .prop('selected', false);

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
    jQuery.validator.addMethod("minimum_limit", function(event, value) {
        var counts = $("#file").get(0).files.length;
        if (counts >= 5) {
            return false;
        } else {

            return true;
        }
    }, 'You can only upload only 5 files.');
    $.validator.addMethod("alpha", function(value, element) {
        return this.optional(element) || value == value.match(/^[a-zA-Z\s]+$/);
    }, 'Please no Numeric value');

    $.validator.addMethod("CheckEXT", function(value, element) {
        var ext = value.match(/\.(.+)$/)[1];
        switch (ext) {
            case 'doc':
                return true;
                break;
            case 'pdf':
                return true;
                break;
            case 'docx':
                return true;
                break;
            default:
                return false;
        }
    }, 'This is not an allowed file type');


    jQuery.validator.addMethod("Chk_File_Size", function(event, value) {
        var flag = true;
        var selection = document.getElementById('file');
        for (var i = 0; i < selection.files.length; i++) {
            var file = selection.files[i].size;
            var FIVE_MB = Math.round(1024 * 1024 * 10);
            if (file > FIVE_MB) {
                flag = false;
            }
        }
        return flag;
    }, 'Upload file having maximum size of 10MB.');
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
$(document).ready(function() {
    $('#phoneNo').mask("(000) 000-0000", { placeholder: "(xxx) xxx-xxxx" });
});