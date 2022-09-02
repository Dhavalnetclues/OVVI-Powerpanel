var ValidatePassword = function () {
    var handlePassword = function () {
        $("#passwordprotect_form").validate({
            errorElement: 'span', //default input error message container
            errorClass: 'help-block', // default input error message class
            ignore: [],
            rules: {
                passwordprotect: {
                    required: true,
                    xssProtection: true,
                    no_url: true,
                    minlength: 6,
                    maxlength: 20,
                },
            },
            messages: {
                passwordprotect: {
                    required: "Password is required"
                },
            },
            errorPlacement: function (error, element) {
                error.insertAfter(element.parent('.form-group'));
            },
            invalidHandler: function (event, validator) { //display error alert on form submit   
                $('.alert-danger', $('#passwordprotect_form')).show();
            },
            highlight: function (element) { // hightlight error inputs
                $(element).closest('.form-group').addClass('has-error'); // set error class to the control group
            },
            unhighlight: function (element) {
                $(element).closest('.form-group').removeClass('has-error'); // set error class to the control group
            },
            submitHandler: function (form) {
            }
        });
    }
    return {
//main function to initiate the module
        init: function () {
            handlePassword();
        }
    };
}();

$(document).on("click", '#submit', function(event) {
    event.preventDefault();
    if($("#passwordprotect_form").valid()) {
        let data = {
            id:$("#id").val(),
            passwordprotect: $("#passwordprotect").val(),
            tablename: $("#tablename").val(),
        }
        $.ajax({
            url: passwordProtectURL,
            type: 'POST',
            dataType: 'JSON',
            data: data,
            success: function (response) {
                if (response.validatorErrors != null) {
                    $("#error").html(response.validatorErrors);
                } else {
                    $("#passpopup").hide();
                    $("#pageContent").html(response);
                    $("#pageContent").removeClass('hide');
                    aosFunction();
                    svgIcon(".n-icon");
                    $(".cms li").wrapInner("<span></span>");
                }
            },
            complete: function () {
            }
        });
    }
});

jQuery(document).ready(function() {
    ValidatePassword.init();
    
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
})

