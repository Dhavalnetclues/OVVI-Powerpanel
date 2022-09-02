var ValidateSubscribe = function () {
    var handleSubscribe = function () {
        $("#cms_form").validate({
            errorElement: 'span', //default input error message container
            errorClass: 'help-block', // default input error message class
            ignore: [],
            rules: {
                txtDescription: {
                    required: true
                }
            },
            messages: {
                contents: {
                    required: "Description is required"
                }
            },
            errorPlacement: function (error, element) {
                if (element.attr('name') == 'contents') {
                    error.insertAfter($(".contents"));
                } else {
                    error.insertAfter(element);
                }
            },
            invalidHandler: function (event, validator) { //display error alert on form submit   
                $('.alert-danger', $('#feedback_form')).show();
            },
            highlight: function (element) { // hightlight error inputs
                $(element).closest('.form-group').addClass('has-error'); // set error class to the control group
            },
            unhighlight: function (element) {
                $(element).closest('.form-group').removeClass('has-error'); // set error class to the control group
            },
            submitHandler: function (form) {
                var formSerialize = $("#cms_form").serialize();
                var ck_data = CKEDITOR.instances['txtDescription'].getData();
                $.ajax({
                    url: form.action,
                    start: SetBackGround(),
                    type: form.method,
                    dataType: 'JSON',
                    data: formSerialize,
                    success: function (response) {
                        UnSetBackGround();
                        if (response.validatorErrors != null) {
                            $.each(response.validatorErrors, function (key, value) {
                                var errorInput = key;
                                var error = '<span id=' + errorInput + '-error" class="error" style="">' + value + '</span>';
                                $('#' + errorInput + '-error').remove();
                                if (key == "chrSatisfied") {
                                    $(error).insertAfter($('#Satisfied'));
                                } else if (key == "chrCategory") {
                                    $(error).insertAfter($('.feedback_cat'));
                                } else {
                                    $(error).insertAfter($('#' + key + ''));
                                }
                            });
                        } else {
                            alert(response.success);
                            $("#details_cms").show();
                            $("#ck_cms").hide();
                            location.reload();
                        }
                    },
                    complete: function () {
                        grecaptcha.reset();
                    }
                });
            }
        });
        $('#feedback_form input').keypress(function (e) {
            if (e.which == 13) {
                if ($('#feedback_form').validate().form()) {
                    alert(3)
                }
                return false;
            }
        });
    }
    return {
//main function to initiate the module
        init: function () {
            handleSubscribe();
        }
    };
}();
$.validator.addMethod("emailFormat", function (value, element) {
    // allow any non-whitespace characters as the host part
    return this.optional(element) || /^[_A-Za-z0-9-]+(\.[_A-Za-z0-9-]+)*@[A-Za-z0-9-]+(\.[A-Za-z0-9-]+)*(\.[A-Za-z]{2,4})$/.test(value);
}, 'Enter valid email format');
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
$.validator.addMethod("phonenumber", function (value, element) {
    var numberPattern = /\d+/g;
    var newVal = value.replace(/\D/g);
    if (parseInt(newVal) <= 0) {
        return false;
    } else {
        return true;
    }
}, 'Please enter a valid phone number.');
ValidateSubscribe.init();
$("#varPhoneNo").bind("paste", function (e) {
    // access the clipboard using the api
    return false;
});
function SetBackGround()
{
    $("body").addClass("blur_loader");
    document.getElementById('loader_div').style.display = 'block';
}
function UnSetBackGround()
{
    document.getElementById('loader_div').style.display = 'none';
    $("body").removeClass("blur_loader");
}

