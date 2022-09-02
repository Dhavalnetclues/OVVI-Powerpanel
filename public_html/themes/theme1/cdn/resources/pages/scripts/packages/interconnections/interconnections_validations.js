/**
 * This method validates news form fields
 * since   2021-02-19
 * author  Ayushi Vora
 */
var Validate = function() {
    var handleServiceCategory = function() {
        $("#frmInterconnections").validate({
            errorElement: 'span', //default input error message container
            errorClass: 'help-block', // default input error message class
            rules: {
                sector: {
                    required: true,
                    xssProtection: true,
                    no_url: true
                },
                title: {
                    required: true,
                    xssProtection: true,
                    no_url: true
                },
                start_date_time: {
                    required: false,
                },
                display_order: {
                    required: true,
                    minStrict: true,
                    number: true,
                    noSpace: true,
                    xssProtection: true,
                    no_url: true
                },
                txtShortDescription: {
                    xssProtection: true,
                    no_url: true
                },
            },
            messages: {
                title: {
                    required: Lang.get('validation.required', { attribute: Lang.get('template.name') })
                },
                sector: {
                    required: 'Sector is required.'
                },
                start_date_time: {
                    required: "Start date field is required.",
                },
                display_order: {
                    required: Lang.get('validation.required', { attribute: Lang.get('template.displayorder') })
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
                var errors = validator.numberOfInvalids();
                if (errors) {
                    $.loader.close(true);
                }
                $('.alert-danger', $('#frmInterconnections')).show();
            },
            highlight: function(element) { // hightlight error inputs
                $(element).closest('.form-group').addClass('has-error'); // set error class to the control group
            },
            unhighlight: function(element) {
                $(element).closest('.form-group').removeClass('has-error'); // set error class to the control group
            },
            submitHandler: function(form) {
                $('body').loader(loaderConfig);
                form.submit();
                $("button[type='submit']").attr('disabled', 'disabled');
                return false;
            }
        });
        $('#frmInterconnections input').keypress(function(e) {
            if (e.which == 13) {
                if ($('#frmInterconnections').validate().form()) {
                    $('#frmInterconnections').submit(); //form validation success, call ajax form submit
                }
                return false;
            }
        });
    }
    return {
        //main function to initiate the module
        init: function() {
            handleServiceCategory();
        }
    };
}();

jQuery(document).ready(function() {
    Validate.init();
    jQuery.validator.addMethod("noSpace", function(value, element) {
        if (value.trim().length <= 0) {
            return false;
        } else {
            return true;
        }
    }, "This field is required");

    $('#interconnection_date').datepicker({
        autoclose: true,
        minuteStep: 5,
        format: 'dd-mm-yyyy',
    });
});

jQuery.validator.addMethod("minStrict", function(value, element) {
    // allow any non-whitespace characters as the host part
    if (value > 0) {
        return true;
    } else {
        return false;
    }
}, 'Display order must be a number higher than zero');
$('input[name=title]').change(function() {
    var title = $(this).val();
    var trim_title = title.trim();
    if (trim_title) {
        $(this).val(trim_title);
        return true;
    }
});