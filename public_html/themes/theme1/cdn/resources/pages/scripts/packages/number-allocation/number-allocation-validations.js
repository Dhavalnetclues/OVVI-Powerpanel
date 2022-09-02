/**
 * This method validates FAQs form fields
 * since   2016-12-24
 * author  NetQuick
 */
var Validate = function() {
    var handleBlogs = function() {
        $("#frmNumberAllocation").validate({
            errorElement: 'span',
            errorClass: 'help-block',
            ignore: [],
            rules: {
                sector: {
                    required: true,
                    noSpace: true
                },
                nxx: {
                    required: true,
                    noSpace: true,
                    xssProtection: true,
                    no_url: true
                },
                category_id: {
                    required: true,
                    noSpace: true
                },
                service: {
                    required: true,
                    noSpace: true,
                    xssProtection: true,
                    no_url: true
                },
                order: {
                    required: true,
                    number: true,
                    noSpace: true,
                    minStrict: true
                },
            },
            messages: {
                sector: { required: 'Please enter sector.' },
                nxx: { required: 'Please enter nxx#.' },
                category_id: "Please select company category.",
                service: { required: "Please enter service." },
                order: { required: Lang.get('validation.required', { attribute: Lang.get('template.displayorder') }) },
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
            invalidHandler: function(event, validator) {
                var errors = validator.numberOfInvalids();
                if (errors) {
                    $.loader.close(true);
                }
                $('.alert-danger', $('#frmNumberAllocation')).show();
            },
            highlight: function(element) {
                $(element).closest('.form-group').addClass('has-error');
            },
            unhighlight: function(element) {
                $(element).closest('.form-group').removeClass('has-error');
            },
            submitHandler: function(form) {
                $('body').loader(loaderConfig);
                form.submit();
                $("button[type='submit']").attr('disabled', 'disabled');
                return false;
            }
        });

        $('#tags-input input').on('keypress', function(e) {
            if (e.keyCode == 13) {
                e.keyCode = 188;
                e.preventDefault();
            };
        });
        $('#frmNumberAllocation input').on('keypress', function(e) {
            if (e.keyCode == 13 && e.keyCode != 188) {
                if ($('#frmNumberAllocation').validate().form()) {
                    $('#frmNumberAllocation').submit();
                }
                return false;
            }
        });
    }
    return {
        init: function() {
            handleBlogs();
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
});

jQuery.validator.addMethod("noSpace", function(value, element) {
    if (value.trim().length <= 0) {
        return false;
    } else {
        return true;
    }
}, "This field is required");

jQuery.validator.addMethod("minStrict", function(value, element) {
    if (value > 0) {
        return true;
    } else {
        return false;
    }
}, 'Display order must be a number higher than zero');

jQuery.validator.addMethod("avoidonlyzero", function(value, element) {
    var newVal = value;
    if (newVal <= 0) {
        return false;
    } else {
        return true;
    }
}, "Please enter a valid value.");

$('input[type=text]').change(function() {
    var input = $(this).val();
    var trim_input = input.trim();
    if (trim_input) {
        $(this).val(trim_input);
        return true;
    }
});

$(document).on("change", '#category_id', function(event) {
    event.preventDefault();
    let categoryValue = event.target.value
    if (categoryValue == 'other') {
        $("#companyCategoryBlock").css("display", "block");
        $("#companyCategory").rules('add', {
            required: true
        });
    } else {
        $("#companyCategoryBlock").css("display", "none");
        $("#companyCategory").rules('add', {
            required: false
        });
    }
});