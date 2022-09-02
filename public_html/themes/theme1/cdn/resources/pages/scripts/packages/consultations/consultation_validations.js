/**
 * This method validates Consultations form fields
 * since   2016-12-24
 * author  NetQuick
 */
var Validate = function() {
    var handleConsultations = function() {
        $("#frmConsultations").validate({
            errorElement: 'span', //default input error message container
            errorClass: 'help-block', // default input error message class
            ignore: [],
            rules: {
                title: {
                    required: true,
                    noSpace: true,
                    xssProtection: true,
                    no_url: true
                },
                start_date_time: {
                    required: true,
                },
                end_date_time: {
                    // daterange: false,
                    required: {
                        depends: function() {
                            var isChecked = $('#consulation_end_date').attr('data-exp');
                            if (isChecked == 0) {
                                return $('input[name=end_date_time]').val().length == 0;
                            }
                        }
                    }
                },
                varMetaTitle: {
                    required: true,
                    noSpace: true,
                    xssProtection: true,
                    no_url: true
                },
                short_description: {
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
                varExternalLink: {
                    url: true,
                },
                'new-alias': {
                    specialCharacterCheck: true,
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
                'category_id': {
                    required: true
                },
            },
            messages: {
                title: { required: Lang.get('validation.required', { attribute: Lang.get('template.name') }) },
                short_description: { required: Lang.get('validation.required', { attribute: Lang.get('template.shortdescription') }) },
                display_order: { required: Lang.get('validation.required', { attribute: Lang.get('template.displayorder') }) },
                varMetaTitle: { required: Lang.get('validation.required', { attribute: Lang.get('template.metatitle') }) },
                varMetaDescription: { required: Lang.get('validation.required', { attribute: Lang.get('template.metadescription') }) },
                varExternalLink: {
                    url: "Please enter a valid URL.",
                },
                start_date_time: {
                    required: "Start date field is required.",
                },
                end_date_time: {
                    required: Lang.get('validation.required', { attribute: Lang.get('template.enddate') }),
                    // daterange: 'The end date must be a greater than start date.'
                },
                new_password: {
                    required: Lang.get('validation.required', { attribute: 'Password' }),
                    passwordrules: 'Please follow rules for password.'
                },
                'category_id': {
                    required: 'Category is required.'
                },
            },
            errorPlacement: function(error, element) {
                if (element.parent('.input-group').length) {
                    error.insertAfter(element.parent());
                } else if (element.hasClass('select2')) {
                    error.insertAfter(element.next('span'));
                } else if (element.attr('name') == 'description') {
                    error.insertAfter(element.next().next('span'));
                } else {
                    error.insertAfter(element);
                }
            },
            invalidHandler: function(event, validator) { //display error alert on form submit  
                var errors = validator.numberOfInvalids();
                if (errors) {
                    $.loader.close(true);
                }
                $('.alert-danger', $('#frmConsultations')).show();
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

        $('#tags-input input').on('keypress', function(e) {
            if (e.keyCode == 13) {
                e.keyCode = 188;
                e.preventDefault();
            };
        });

        $('#frmConsultations input').keypress(function(e) {
            if (e.which == 13 && e.keyCode != 188) {
                if ($('#frmConsultations').validate().form()) {
                    $('#frmConsultations').submit(); //form validation success, call ajax form submit
                }
                return false;
            }
        });
    }
    return {
        //main function to initiate the module
        init: function() {
            handleConsultations();
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

    var isChecked = $('#consulation_end_date').attr('data-exp');
    if (isChecked == 1) {
        $('.expdatelabel').removeClass('no_expiry');
        $('.expiry_lbl').text('Set Expiry');
        $(".expirydate").hide();
        $('#consulation_end_date').attr('disabled', 'disabled');
    } else {
        $('.expdatelabel').addClass('no_expiry');
        $('.expiry_lbl').text('No Expiry');
        $('#consulation_end_date').removeAttr('disabled');
    }

    $("#consulation_start_date").datetimepicker({
        format: 'd-m-Y',
        onShow: function(ct) {
            this.setOptions({})
        },
        timepicker: false
    });

    $("#consulation_end_date").datetimepicker({
        format: 'd-m-Y',
        onShow: function(ct) {
            this.setOptions({})
        },
        timepicker: false
    });

});
jQuery.validator.addMethod("phoneFormat", function(value, element) {
    // allow any non-whitespace characters as the host part
    return this.optional(element) || /((\(\d{3}\) ?)|(\d{3}-))?\d{3}-\d{4}/.test(value);
}, 'Please enter a valid phone number.');

jQuery.validator.addMethod("daterange", function(value, element) {
    var fromDateTime = $('#consulation_start_date').val();
    var toDateTime = $("#consulation_end_date").val();
    var isChecked = $('#consulation_end_date').attr('data-exp');
    if (isChecked == 0) {
        toDateTime = new Date($("#consulation_end_date").val());
        fromDateTime = new Date($("#consulation_start_date").val());
        return toDateTime >= fromDateTime && fromDateTime <= toDateTime;
    } else {
        return true;
    }
}, "The end date must be a greater than start date.");

$('input[type=text]').change(function() {
    var input = $(this).val();
    var trim_input = input.trim();
    if (trim_input) {
        $(this).val(trim_input);
        return true;
    }
});

$('.fromButton').click(function() {
    $('#consulation_start_date').datetimepicker('show');
});
$('.toButton').click(function() {
    $('#consulation_end_date').datetimepicker('show');
});

$(document).on("change", '#consulation_end_date', function() {
    $(this).attr('data-newvalue', $(this).val());
});

$('#noexpiry').click(function() {
    var isChecked = $('#consulation_end_date').attr('data-exp');

    if (isChecked == 0) {
        $('.expdatelabel').removeClass('no_expiry');
        $('.expiry_lbl').text('Set Expiry');
        $('#consulation_end_date').attr('data-exp', '1');
        $('#consulation_end_date').attr('disabled', 'disabled');
        $(".expirydate").hide();
        $("#consulation_end_date").val(null);
        $('#consulation_end_date').val('');
        $('.expirydate').next('span.help-block').html('');
        $('.expirydate').parent('.form-group').removeClass('has-error');
    } else {
        $('.expdatelabel').addClass('no_expiry');
        $('.expiry_lbl').text('No Expiry');
        $('#consulation_end_date').attr('data-exp', '0');
        $('#consulation_end_date').removeAttr('disabled');
        $(".expirydate").show();
        if ($('#consulation_end_date').attr('data-newvalue').length > 0) {
            $("#consulation_end_date").val($('#consulation_end_date').attr('data-newvalue'));
        } else {
            $("#consulation_end_date").val('');
        }
    }
});