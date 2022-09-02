/**
 * This method validates careerss form fields
 * since   2016-12-24
 * author  NetQuick
 */
var Validate = function() {
    var handleCaionCatelogues = function() {
        $("#frmcareers").validate({
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
                sector: {
                    required: true,
                },
                rollType: {
                    required: true,
                },
                webType: {
                    required: true,
                },
                salary: {
                    required: true,
                    noSpace: true,
                    xssProtection: true,
                    no_url: true,
                    notOnlyZero: true,
                },
                // refNo: {
                //     required: true,
                //     noSpace: true,
                //     xssProtection: true,
                //     no_url: true
                // },
                employmentType: {
                    required: true,
                    noSpace: true,
                    xssProtection: true,
                    no_url: true
                },
                position: {
                    required: true,
                    digits: true,
                    maxlength: 3,
                    notOnlyZero: true,
                    noSpace: true,
                    xssProtection: true
                },
                experience: {
                    required: true,
                    noSpace: true,
                    digits: true,
                    maxlength: 2,
                    notOnlyZero: true,
                    xssProtection: true
                },
                requirements: {
                    required: true,
                    noSpace: true,
                    xssProtection: true,
                    no_url: true
                },
                category_id: {
                    required: true,
                    noSpace: true
                },
                description: {
                    required: true
                },
                start_date_time: {
                    required: true,
                },
                end_date_time: {
                    daterange: true,
                    required: {
                        depends: function() {
                            var isChecked = $('#careers_end_date').attr('data-exp');
                            if (isChecked == 0) {
                                return $('input[name=end_date_time]').val().length == 0;
                            }
                        }
                    }
                },
                order: {
                    required: true,
                    number: true,
                    noSpace: true,
                    minStrict: true,
                    xssProtection: true,
                    no_url: true
                },
                varMetaTitle: {
                    required: true,
                    noSpace: true,
                    xssProtection: true,
                    no_url: true
                },
                varMetaDescription: {
                    required: true,
                    noSpace: true,
                    xssProtection: true,
                    no_url: true
                },
                new_password: {
                    required: {
                        depends: function() {
                            if ($('input[name="chrPageActive"][value="PP"]').prop("checked") == true) {
                                return true;
                            } else {
                                return false;
                            }
                        }
                    },
                    passwordrules: {
                        depends: function() {
                            if ($('input[name="chrPageActive"][value="PP"]').prop("checked") == true) {
                                return true;
                            } else {
                                return false;
                            }
                        }
                    },
                    minlength: 6,
                    maxlength: 20
                },
            },
            messages: {
                title: { required: Lang.get('validation.required', { attribute: Lang.get('template.title') }) },
                jobCategory: "Please Select Job Category.",
                sector: "Please Select Sector Type.",
                rollType: "Please Select Roll-Type.",
                webType: "Please Select Website Type.",
                salary: {
                    required: "Please Enter Salary",
                    noSpace: "Spaces Are Not Allowed",
                    // min: "Nagative Value Not Allowed , Value must be Greater Than 0 (zero)",
                },
                employmentType: "Please select employment Type.",
                position: {
                    required: "Please Enter Position",
                    digits: "Only Numeric value Allowed",
                    noSpace: "Spaces Are Not Allowed",
                    maxlength: "You Can Enter Max 3 Digit(s) number"
                },
                experience: {
                    required: "Please Enter Your Experience",
                    digits: "Only Numeric value Allowed",
                    noSpace: "Spaces Are Not Allowed",
                    maxlength: "You Can Enter Max 2 Digit(s) number"
                },
                category_id: "Please select category.",
                description: "Description field is required.",
                order: { required: Lang.get('validation.required', { attribute: Lang.get('template.displayorder') }) },
                varMetaTitle: { required: Lang.get('validation.required', { attribute: Lang.get('template.metatitle') }) },
                varMetaDescription: { required: Lang.get('validation.required', { attribute: Lang.get('template.metadescription') }) },
                start_date_time: {
                    required: "Start date field is required.",
                },
                end_date_time: {
                    required: Lang.get('validation.required', { attribute: Lang.get('template.enddate') }),
                    daterange: 'The end date must be a greater than start date.'
                },
                new_password: {
                    required: Lang.get('validation.required', { attribute: 'Password' }),
                    passwordrules: 'Please follow rules for password.'
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
            invalidHandler: function(event, validator) {
                var errors = validator.numberOfInvalids();
                if (errors) {
                    $.loader.close(true);
                }
                $('.alert-danger', $('#frmcareers')).show();
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
        $('#frmcareers input').keypress(function(e) {
            if (e.which == 13 && e.keyCode != 188) {
                if ($('#frmcareers').validate().form()) {
                    $('#frmcareers').submit();
                }
                return false;
            }
        });
    }
    return {
        init: function() {
            handleCaionCatelogues();
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
    var isChecked = $('#careers_end_date').attr('data-exp');
    if (isChecked == 1) {
        $('.expdatelabel').removeClass('no_expiry');
        $('.expiry_lbl').text('Set Expiry');
        $(".expirydate").hide();
        $('#careers_end_date').attr('disabled', 'disabled');
    } else {
        $('.expdatelabel').addClass('no_expiry');
        $('.expiry_lbl').text('No Expiry');
        $('#careers_end_date').removeAttr('disabled');
    }
});
jQuery(document).ready(function() {
    $('#careers_start_date').datetimepicker({
        format: DEFAULT_DATE_FORMAT + ' ' + DEFAULT_TIME_FORMAT,
        onShow: function() {
            this.setOptions({})
        },
        scrollMonth: false,
        scrollInput: false
    });

    $('#careers_end_date').datetimepicker({
        format: DEFAULT_DATE_FORMAT + ' ' + DEFAULT_TIME_FORMAT,
        onShow: function() {
            this.setOptions({})
        },
        scrollMonth: false,
        scrollInput: false
    });
});


$('.fromButton').click(function() {
    $('#careers_start_date').datetimepicker('show');
});
$('.toButton').click(function() {
    $('#careers_end_date').datetimepicker('show');
});
$(document).on("change", '#careers_end_date', function() {
    $(this).attr('data-newvalue', $(this).val());
});

jQuery.validator.addMethod("daterange", function(value, element) {
    var fromDateTime = $('#careers_start_date').val();
    var toDateTime = $("#careers_end_date").val();
    var isChecked = $('#careers_end_date').attr('data-exp');
    if (isChecked == 0) {
        toDateTime = new Date(toDateTime);
        fromDateTime = new Date(fromDateTime);
        return toDateTime >= fromDateTime && fromDateTime <= toDateTime;
    } else {
        return true;
    }
}, "The end date must be a greater than start date.");

jQuery.validator.addMethod("phoneFormat", function(value, element) {
    return this.optional(element) || /((\(\d{3}\) ?)|(\d{3}-))?\d{3}-\d{4}/.test(value);
}, 'Please enter a valid phone number.');
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

jQuery.validator.addMethod("notOnlyZero", function(value, element) {
    return this.optional(element) || parseInt(value) > 0;
}, "Zero Value Not Allowed");


$('#noexpiry').click(function() {
    var isChecked = $('#careers_end_date').attr('data-exp');

    if (isChecked == 0) {
        $('.expdatelabel').removeClass('no_expiry');
        $('.expiry_lbl').text('Set Expiry');
        $('#careers_end_date').attr('data-exp', '1');
        $('#careers_end_date').attr('disabled', 'disabled');
        $(".expirydate").hide();
        $("#careers_end_date").val(null);
        $('#careers_end_date').val('');
        $('.expirydate').next('span.help-block').html('');
        $('.expirydate').parent('.form-group').removeClass('has-error');
    } else {
        $('.expdatelabel').addClass('no_expiry');
        $('.expiry_lbl').text('No Expiry');
        $('#careers_end_date').attr('data-exp', '0');
        $('#careers_end_date').removeAttr('disabled');
        $(".expirydate").show();
        if ($('#careers_end_date').attr('data-newvalue').length > 0) {
            $("#careers_end_date").val($('#careers_end_date').attr('data-newvalue'));
        } else {
            $("#careers_end_date").val('');
        }
    }
});