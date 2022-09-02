var ValidateReport = function () {
    var handleReportToSend = function () {
        $("#HitsReportForm").validate({
            errorElement: 'span', //default input error message container
            errorClass: 'help-block error', // default input error message class
            ignore: [],
            rules: {
                Report_Name: {
                     required: true,
                    messageValidation: true,
                    xssProtection: true,
                    no_url: true
                },
                Report_email: {
                      required: true,
                    emailFormat: true,
                },
            },
            messages: {
                Report_Name: {
                    required: "Name is required",
                },
                Report_email: {
                    required: "Email is required",
                },
            },
            errorPlacement: function (error, element) {
                if (element.attr('id') == 'g-recaptcha-response-1') {
                    error.insertAfter(element.parent());
                } else {
                    error.insertAfter(element);
                }
            },
            invalidHandler: function (event, validator) { //display error alert on form submit   
                $('.alert-danger', $('#HitsReportForm')).show();
            },
            highlight: function (element) { // hightlight error inputs
                $(element).closest('.form-group').addClass('has-error'); // set error class to the control group
            },
            unhighlight: function (element) {
                $(element).closest('.form-group').removeClass('has-error'); // set error class to the control group
            },
            submitHandler: function (form) {
                $("#report_for_submit").attr("disabled", "disabled");
                sendreportsubmit();
                return false;
            }
        });
        $('#HitsReportForm input').keypress(function (e) {
            if (e.which == 13) {
                if ($('#HitsReportForm').validate().form()) {
                    $("#report_for_submit").attr("disabled", "disabled");
                }
                return false;
            }
        });
    }
    return {
        //main function to initiate the module
        init: function () {
            handleReportToSend();
        }
    };
}();


$.validator.addMethod('no_url', function (value, element) {
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

jQuery(document).ready(function () {

    ValidateReport.init();

    $.ajaxSetup({
        headers: {
            'X-CSRF-Token': $('input[name="_token"]').val()
        }
    });
});

jQuery.validator.addMethod("emailFormat", function (value, element) {
    // allow any non-whitespace characters as the host part
    return this.optional(element) || /^[_A-Za-z0-9-]+(\.[_A-Za-z0-9-]+)*@[A-Za-z0-9-]+(\.[A-Za-z0-9-]+)*(\.[A-Za-z]{2,4})$/.test(value);
}, 'Enter valid email format');
jQuery.validator.addMethod("messageValidation", function (value, element) {
    // allow any non-whitespace characters as the host part
    return this.optional(element) || /<(\w+)((?:\s+\w+(?:\s*=\s*(?:(?:"[^"]*")|(?:'[^']*')|[^>\s]+))?)*)\s*(\/?)>/.test(value) == false ? true : false;
}, 'Enter valid message');
jQuery.validator.addMethod("xssProtection", function (value, element) {
    // allow any non-whitespace characters as the host part
    return this.optional(element) || /<(\w+)((?:\s+\w+(?:\s*=\s*(?:(?:"[^"]*")|(?:'[^']*')|[^>\s]+))?)*)\s*(\/?)>/.test(value) == false ? true : false;
}, 'Enter valid input');
$.validator.addMethod("check_special_char", function (value, element) {
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
$.validator.addMethod('no_url', function (value, element) {
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


$(document).on("click", "#Send_Report_Email", function () {
    var selectedVal = $("#pageHitsChartFilter option:selected").val();
    $("#HitsReportForm")[0].reset();
    $("#report_for_submit").removeAttr("disabled");
    $("#HitsReportForm").find(".success").hide();
    $("#HitsReportForm").find("label.error").hide();
    $("#HitsReportForm").find(".success").text("");
    $("#HitsReportForm").find("label.error").text("");
    $("#year").val(selectedVal);
    $('#ReportModel').modal('show');
});

function sendreportsubmit() {
    if ($("#HitsReportForm").valid()) {
        var frmData = $('#HitsReportForm').serialize();
        jQuery.ajax({
            type: "POST",
            url: Email_Send_Report_URL,
            data: frmData,
            dataType: 'json',
            async: true,
            success: function (data) {
                if (data.success == 1) {
                    alert(data.msg);
                    location.reload(true);
                } else {
                    $("#HitsReportForm").find("label.error").text(data.msg);
                    $("#HitsReportForm").find("label.error").show();
                    $("#HitsReportForm").find(".success").hide();
                }
            }
        });
    }
}
;