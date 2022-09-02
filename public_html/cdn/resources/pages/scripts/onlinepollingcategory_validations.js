/**
 * This method validates FAQs form fields
 * since   2016-12-24
 * author  NetQuick
 */
var Validate = function () {
    var handleonlinepollingcategory = function () {
        $("#frmonlinepollingcategory").validate({
            errorElement: 'span',
            errorClass: 'help-block',
            ignore: [],
            rules: {
                title: {
                    required: true,
                    noSpace: true,
                    xssProtection: true,
                    no_url: true
                },
                display_order: {
                    required: true,
                    minStrict: true,
                    number: true,
                    noSpace: true,
                    xssProtection: true,
                    no_url: true
                },
            },
            messages: {
                title:{required: Lang.get('validation.required', {attribute: Lang.get('template.title')})},
                display_order: {required: Lang.get('validation.required', {attribute: Lang.get('template.displayorder')})},
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
                $('.alert-danger', $('#frmonlinepollingcategory')).show();
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
                $("button[type='submit']").attr('disabled','disabled');
                return false;
            }
        });
        $('#frmonlinepollingcategory input').keypress(function (e) {
            if (e.which == 13) {
                if ($('#frmonlinepollingcategory').validate().form()) {
                    $('#frmonlinepollingcategory').submit();
                }
                return false;
            }
        });
    }
    return {
        init: function () {
            handleonlinepollingcategory();
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
jQuery.validator.addMethod("noSpace", function (value, element) {
    if (value.trim().length <= 0) {
        return false;
    } else {
        return true;
    }
}, "This field is required");
jQuery.validator.addMethod("minStrict", function (value, element) {
    if (value > 0) {
        return true;
    } else {
        return false;
    }
}, 'Display order must be a number higher than zero');
$('input[type=text]').change(function () {
    var input = $(this).val();
    var trim_input = input.trim();
    if (trim_input) {
        $(this).val(trim_input);
        return true;
    }
});
