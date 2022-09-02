/**
 * This method validates FAQs form fields
 * since   2016-12-24
 * author  NetQuick
 */
var Validate = function () {
    var handledepartment = function () {
        $("#frmblockips").validate({
            errorElement: 'span',
            errorClass: 'help-block',
            ignore: [],
            rules: {
                ip_address: {
                    required: true,
                    noSpace: true,
                    checkmultipleip: true
                },
            },
            messages: {
                ip_address: {
                    required: 'IP address field is required.',
                    checkmultipleip: 'Please enter valid IP address.'
                },
            },
            errorPlacement: function (error, element) {
                if (element.parent('.input-group').length) {
                    error.insertAfter(element.parent());
                } else if (element.hasClass('select2')) {
                    error.insertAfter(element.next('span'));
                } else {
                    error.insertAfter(element);
                }
            },
            invalidHandler: function (event, validator) {
                var errors = validator.numberOfInvalids();
                if (errors) {
                    $.loader.close(true);
                }
                $('.alert-danger', $('#frmblockips')).show();
            },
            highlight: function (element) {
                $(element).closest('.form-group').addClass('has-error');
            },
            unhighlight: function (element) {
                $(element).closest('.form-group').removeClass('has-error');
            },
            submitHandler: function (form) {
                $('body').loader(loaderConfig);
                form.submit();
                return false;
            }
        });
        $('#frmblockips input').keypress(function (e) {
            if (e.which == 13) {
                if ($('#frmblockips').validate().form()) {
                    $('#frmblockips').submit();
                }
                return false;
            }
        });
    }
    return {
        init: function () {
            handledepartment();
        }
    };
}();
jQuery(document).ready(function () {
    Validate.init();
    jQuery.validator.addMethod("noSpace", function (value, element) {
        if (value.trim().length <= 0) {
            return false;
        } else {
            return true;
        }
    }, "This field is required");
});
$.validator.addMethod('checkmultipleip', function (value, element)
{
//    var Ip = value;
//    var count = Ip.length;
    var flag = true;
//    var i;
//    for (i = 0; i < count; i++)
//    {
    if (/^(25[0-5]|2[0-4][0-9]|[01]?[0-9][0-9]?)\.(25[0-5]|2[0-4][0-9]|[01]?[0-9][0-9]?)\.(25[0-5]|2[0-4][0-9]|[01]?[0-9][0-9]?)\.(25[0-5]|2[0-4][0-9]|[01]?[0-9][0-9]?)$/.test(value)) {
        flag = true;
    } else {
        flag = false;
    }
//    }
    return flag;
}, 'Please enter valid IP address.');
$('input[type=text]').change(function () {
    var input = $(this).val();
    var trim_input = input.trim();
    if (trim_input) {
        $(this).val(trim_input);
        return true;
    }
});


