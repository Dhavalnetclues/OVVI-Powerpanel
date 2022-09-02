var ValidateEmail = function() {
    var handleEmailFrm = function() {
        $("#frmEmailverify").validate({
            errorElement: 'span', //default input error message container
            errorClass: 'help-block error', // default input error message class
            ignore: [],
            rules: {
                verifyEmail: {
                    required: true,
                    xssProtection: true,
                    noSpace: true,
                    no_url: true,
                    email: true,
                },
            },
            messages: {
                verifyEmail: {
                    required: "Email is required",
                },
            },
            errorPlacement: function(error, element) {
                error.insertAfter(element);
            },
            invalidHandler: function(event, validator) { //display error alert on form submit   
                $('.alert-danger', $('#frmEmailverify')).show();
            },
            highlight: function(element) { // hightlight error inputs
                $(element).closest('.form-group').addClass('has-error'); // set error class to the control group
            },
            unhighlight: function(element) {
                $(element).closest('.form-group').removeClass('has-error'); // set error class to the control group
            },
            submitHandler: function(form) {
                $("#Email_submit").attr("disabled", "disabled");
                EmailVarifyOtpSubmit();
                return false;
            }
        });
        $('#frmEmailverify input').keypress(function(e) {
            if (e.which == 13) {
                if ($('#frmEmailverify').validate().form()) {
                    $("#Email_submit").attr("disabled", "disabled");
                }
                return false;
            }
        });
    }
    return {
        //main function to initiate the module
        init: function() {
            handleEmailFrm();
        }
    };
}();
var ValidateOtp = function() {
    var handleOtpFrm = function() {
        $("#frmOtpverify").validate({
            errorElement: 'span', //default input error message container
            errorClass: 'help-block error', // default input error message class
            ignore: [],
            rules: {
                otp: {
                    required: true,
                    maxlength: 6,
                    minlength: 6,
                    xssProtection: true,
                    noSpace: true,
                    no_url: true,
                },
            },
            messages: {
                otp: {
                    required: "OTP is required",
                },
            },
            errorPlacement: function(error, element) {
                error.insertAfter(element);
            },
            invalidHandler: function(event, validator) { //display error alert on form submit   
                $('.alert-danger', $('#frmOtpverify')).show();
            },
            highlight: function(element) { // hightlight error inputs
                $(element).closest('.form-group').addClass('has-error'); // set error class to the control group
            },
            unhighlight: function(element) {
                $(element).closest('.form-group').removeClass('has-error'); // set error class to the control group
            },
            submitHandler: function(form) {
                $("#Otp_Verify").attr("disabled", "disabled");
                OtpVarifySubmit();
                return false;
            }
        });
        $('#frmOtpverify input').keypress(function(e) {
            if (e.which == 13) {
                if ($('#frmOtpverify').validate().form()) {
                    $("#Otp_Verify").attr("disabled", "disabled");
                }
                return false;
            }
        });
    }
    return {
        //main function to initiate the module
        init: function() {
            handleOtpFrm();
        }
    };
}();
var ValidateForword = function() {
    var handleForwordToFrm = function() {
        $("#frmSecurityQuestions").validate({
            errorElement: 'span', //default input error message container
            errorClass: 'help-block error', // default input error message class
            ignore: [],
            rules: {
                Question1: {
                    required: true,
                    xssProtection: true,
                    noSpace: true,
                    no_url: true
                },
                Question2: {
                    required: true,
                    xssProtection: true,
                    noSpace: true,
                    no_url: true
                },
                Question3: {
                    required: true,
                    xssProtection: true,
                    noSpace: true,
                    no_url: true
                },
                Answer1: {
                    required: true,
                    xssProtection: true,
                    noSpace: true,
                    no_url: true
                },
                Answer2: {
                    required: true,
                    xssProtection: true,
                    noSpace: true,
                    no_url: true
                },
                Answer3: {
                    required: true,
                    xssProtection: true,
                    noSpace: true,
                    no_url: true
                },
            },
            messages: {
                Question1: {
                    required: "Question1 is required",
                },
                Question2: {
                    required: "Question2 is required",
                },
                Question3: {
                    required: "Question3 is required",
                },
                Answer1: {
                    required: "Answer1 is required",
                },
                Answer2: {
                    required: "Answer2 is required",
                },
                Answer3: {
                    required: "Answer3 is required",
                },
            },
            errorPlacement: function(error, element) {
                if (element.parent('.input-group').length) {
                    error.insertAfter(element.parent());
                } else if (element.hasClass('select2')) {
                    error.insertAfter(element.next('span'));
                } else {
                    error.insertAfter(element);
                }
            },
            invalidHandler: function(event, validator) { //display error alert on form submit   
                $('.alert-danger', $('#frmSecurityQuestions')).show();
            },
            highlight: function(element) { // hightlight error inputs
                $(element).closest('.form-group').addClass('has-error'); // set error class to the control group
            },
            unhighlight: function(element) {
                $(element).closest('.form-group').removeClass('has-error'); // set error class to the control group
            },
            submitHandler: function(form) {
                $("#Questions_submit").attr("disabled", "disabled");
                leadAnswersubmit();
                return false;
            }
        });
        $('#frmSecurityQuestions input').keypress(function(e) {
            if (e.which == 13) {
                if ($('#frmSecurityQuestions').validate().form()) {
                    $("#Questions_submit").attr("disabled", "disabled");
                }
                return false;
            }
        });
    }
    return {
        //main function to initiate the module
        init: function() {
            handleForwordToFrm();
        }
    };
}();
jQuery.validator.addMethod("xssProtection", function(value, element) {
    return this.optional(element) || /<(\w+)((?:\s+\w+(?:\s*=\s*(?:(?:"[^"]*")|(?:'[^']*')|[^>\s]+))?)*)\s*(\/?)>/.test(value) == false ? true : false;
}, 'Enter valid input');
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
jQuery(document).ready(function() {
    ValidateForword.init();
    ValidateEmail.init();
    ValidateOtp.init();
    $.ajaxSetup({
        headers: {
            'X-CSRF-Token': $('input[name="_token"]').val()
        }
    });
});

function EmailVarifyOtpSubmit() {
    if ($("#frmEmailverify").valid()) {
        var frmData = $('#frmEmailverify').serialize();
        jQuery.ajax({
            type: "POST",
            url: step_URL_Email_Otp,
            data: frmData,
            dataType: 'json',
            async: true,
            success: function(data) {
                if (data.success == 1) {
                    $("#Email_submit").removeAttr("disabled");
                    $("#email_submit").hide();
                    $("#otp_div").show();
                    $("#Edit").attr("style", "display:none")
                    $("#verifyEmail").attr("disabled", "disabled");
                }
            }
        });
    }
}

function OtpVarifySubmit() {
    if ($("#frmOtpverify").valid()) {
        var frmData = $('#frmOtpverify').serialize();
        jQuery.ajax({
            type: "POST",
            url: step_URL_Otp_verify,
            data: frmData,
            dataType: 'json',
            async: true,
            success: function(response) {
                if (response.validatorErrors != null) {
                    alert(response.validatorErrors);
                    $("#Otp_Verify").removeAttr("disabled");
                } else {
                    $("#2_Step").removeAttr("disabled");
                    $('#otp').val("");
                    $("#Otp_Verify").removeAttr("disabled");
                    $("#otp_div").hide();
                    if (response.success == 'N') {
                        $("#verify").hide();
                    }
                    location.reload();
                }
            }
        });
    }
}

function leadAnswersubmit() {
    if ($("#frmSecurityQuestions").valid()) {
        var frmData = $('#frmSecurityQuestions').serialize();
        $('body').loader(loaderConfig);
        jQuery.ajax({
            type: "POST",
            url: Security_URL_Add,
            data: frmData,
            dataType: 'json',
            async: true,
            success: function(data) {
                $.loader.close(true);
                if (data.success == 1) {
                    $("#Questions_submit").removeAttr("disabled");
                    $(".successMSG").text(data.msg);
                    $(".successMSG").show();
                    setTimeout(function() {
                        $(".successMSG").hide();
                    }, 3000);
                }
            }
        });
    }
}

$('#Question1').on('change', function() {
    var id = this.value;
    $("#Question2 option[disabled]").removeAttr("disabled");
    $("#Question3 option[disabled]").removeAttr("disabled");
    var Question2 = $("#Question2").val();
    if (Question2 != '') {
        $("#Question3 option[value=" + Question2 + "]").attr('disabled', 'disabled');
    }
    var Question3 = $("#Question3").val();
    if (Question3 != '') {
        $("#Question2 option[value=" + Question3 + "]").attr('disabled', 'disabled');
    }
    $("#Question2 option[value=" + id + "]").attr('disabled', 'disabled');
    $("#Question3 option[value=" + id + "]").attr('disabled', 'disabled');
});
$('#Question2').on('change', function() {
    var id = this.value;
    $("#Question1 option[disabled]").removeAttr("disabled");
    $("#Question3 option[disabled]").removeAttr("disabled");
    var Question1 = $("#Question1").val();
    if (Question1 != '') {
        $("#Question3 option[value=" + Question1 + "]").attr('disabled', 'disabled');
    }
    var Question3 = $("#Question3").val();
    if (Question3 != '') {
        $("#Question1 option[value=" + Question3 + "]").attr('disabled', 'disabled');
    }
    $("#Question1 option[value=" + id + "]").attr('disabled', 'disabled');
    $("#Question3 option[value=" + id + "]").attr('disabled', 'disabled');
});
$('#Question3').on('change', function() {
    var id = this.value;
    $("#Question1 option[disabled]").removeAttr("disabled");
    $("#Question2 option[disabled]").removeAttr("disabled");

    var Question2 = $("#Question2").val();
    if (Question2 != '') {
        $("#Question1 option[value=" + Question2 + "]").attr('disabled', 'disabled');
    }
    var Question1 = $("#Question1").val();
    if (Question1 != '') {
        $("#Question2 option[value=" + Question1 + "]").attr('disabled', 'disabled');
    }
    $("#Question1 option[value=" + id + "]").attr('disabled', 'disabled');
    $("#Question2 option[value=" + id + "]").attr('disabled', 'disabled');
});