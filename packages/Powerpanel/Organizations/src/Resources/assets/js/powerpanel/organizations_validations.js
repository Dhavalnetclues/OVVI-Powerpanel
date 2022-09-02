/**
 * This method validates news form fields
 * since   2017-01-31
 * author  Vishal Agrawal
 */
var Validate = function () {
    var handleServiceCategory = function () {
        $("#frmOrganizations").validate({
            errorElement: 'span', //default input error message container
            errorClass: 'help-block', // default input error message class
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
                Designation: {
                    noSpace: true,
                    xssProtection: true,
                    no_url: true
                },
                'new-alias': {
                    specialCharacterCheck: true,
                },
            },
            messages: {
                title:{
                	required: Lang.get('validation.required', {attribute: Lang.get('template.name')})
                },
                display_order: {
                	required: Lang.get('validation.required', {attribute: Lang.get('template.displayorder')})
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
            invalidHandler: function (event, validator) { //display error alert on form submit
                var errors = validator.numberOfInvalids();
                if (errors) {
                    $.loader.close(true);
                }
                $('.alert-danger', $('#frmOrganizations')).show();
            },
            highlight: function (element) { // hightlight error inputs
                $(element).closest('.form-group').addClass('has-error'); // set error class to the control group
            },
            unhighlight: function (element) {
                $(element).closest('.form-group').removeClass('has-error'); // set error class to the control group
            },
            submitHandler: function (form) {
                $('body').loader(loaderConfig);
                form.submit();
                $("button[type='submit']").attr('disabled','disabled');
                return false;
            }
        });
        $('#frmOrganizations input').keypress(function (e) {
            if (e.which == 13) {
                if ($('#frmOrganizations').validate().form()) {
                    $('#frmOrganizations').submit(); //form validation success, call ajax form submit
                }
                return false;
            }
        });
    }
    return {
        //main function to initiate the module
        init: function () {
            handleServiceCategory();
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

jQuery.validator.addMethod("minStrict", function (value, element) {
    // allow any non-whitespace characters as the host part
    if (value > 0) {
        return true;
    } else {
        return false;
    }
}, 'Display order must be a number higher than zero');
$('input[name=title]').change(function () {
    var title = $(this).val();
    var trim_title = title.trim();
    if (trim_title) {
        $(this).val(trim_title);
        return true;
    }
});