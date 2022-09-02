/**
 * This method validates cms pages form fields
 * since   2016-12-24
 * author  NetQuick
 */
var Validate = function() {
    var handleCmsPage = function() {
        $("#frmCmsPage").validate({
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
                sector: {
                    required: true,
                },
                module: {
                    required: true,
                    noSpace: true
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
                start_date_time: {
                    required: true,
                },
                end_date_time: {
                    daterange: true,
                    required: {
                        depends: function() {
                            var isChecked = $('#cmspage_end_date').attr('data-exp');
                            if (isChecked == 0) {
                                return $('input[name=end_date_time]').val().length == 0;
                            }
                        }
                    }
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
            },
            messages: {
                title: {
                    required: Lang.get('validation.required', { attribute: Lang.get('template.title') }),
                },
                sector: {
                    required: 'Sector field is required.',
                },
                module: Lang.get('validation.required', { attribute: Lang.get('template.selectmodule') }),
                varMetaTitle: {
                    required: Lang.get('validation.required', { attribute: Lang.get('template.metatitle') }),
                },
                varMetaDescription: {
                    required: Lang.get('validation.required', { attribute: Lang.get('template.metadescription') }),
                },
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
            invalidHandler: function(event, validator) { //display error alert on form submit
                var errors = validator.numberOfInvalids();
                console.log(validator.numberOfInvalids() + " field(s) are invalid");
                if (errors) {
                    $.loader.close(true);
                }
                $('.alert-danger', $('#frmCmsPage')).show();
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
        $('#frmCmsPage input').keypress(function(e) {
            if (e.which == 13 && e.keyCode != 188) {
                if ($('#frmCmsPage').validate().form()) {
                    $('#frmCmsPage').submit(); //form validation success, call ajax form submit
                }
                return false;
            }
        });
    }
    return {
        //main function to initiate the module
        init: function() {
            handleCmsPage();
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

    var isChecked = $('#cmspage_end_date').attr('data-exp');
    if (isChecked == 1) {
        $('.expdatelabel').removeClass('no_expiry');
        $('.expiry_lbl').text('Set Expiry');
        $(".expirydate").hide();
        $('#cmspage_end_date').attr('disabled', 'disabled');
    } else {
        $('.expdatelabel').addClass('no_expiry');
        $('.expiry_lbl').text('No Expiry');
        $('#cmspage_end_date').removeAttr('disabled');
    }
});
jQuery(document).ready(function() {
    $('#cmspage_start_date').datetimepicker({
        format: DEFAULT_DATE_FORMAT + ' ' + DEFAULT_TIME_FORMAT,
        onShow: function() {
            this.setOptions({})
        },
        scrollMonth: false,
        scrollInput: false
    });

    $('#cmspage_end_date').datetimepicker({
        format: DEFAULT_DATE_FORMAT + ' ' + DEFAULT_TIME_FORMAT,
        onShow: function() {
            this.setOptions({})
        },
        scrollMonth: false,
        scrollInput: false
    });
});

// $('.fromButton').click(function() {
//     $('#cmspage_start_date').datetimepicker('show');
// });
// $('.toButton').click(function() {
//     $('#cmspage_end_date').datetimepicker('show');
// });
$(document).on("change", '#cmspage_end_date', function() {
    $(this).attr('data-newvalue', $(this).val());
});

jQuery.validator.addMethod("daterange", function(value, element) {
    var fromDateTime = $('#cmspage_start_date').val();
    var toDateTime = $("#cmspage_end_date").val();
    var isChecked = $('#cmspage_end_date').attr('data-exp');
    if (isChecked == 0) {
        toDateTime = new Date(toDateTime);
        fromDateTime = new Date(fromDateTime);
        return toDateTime >= fromDateTime && fromDateTime <= toDateTime;
    } else {
        return true;
    }
}, "The end date must be a greater than start date.");

jQuery.validator.addMethod("phoneFormat", function(value, element) {
    // allow any non-whitespace characters as the host part
    return this.optional(element) || /((\(\d{3}\) ?)|(\d{3}-))?\d{3}-\d{4}/.test(value);
}, 'Please enter a valid phone number.');
$('input[name=title]').change(function() {
    var title = $(this).val();
    var trim_title = title.trim();
    if (trim_title) {
        $(this).val(trim_title);
        return true;
    }
});


$('#noexpiry').click(function() {
    var isChecked = $('#cmspage_end_date').attr('data-exp');

    if (isChecked == 0) {
        $('.expdatelabel').removeClass('no_expiry');
        $('.expiry_lbl').text('Set Expiry');
        $('#cmspage_end_date').attr('data-exp', '1');
        $('#cmspage_end_date').attr('disabled', 'disabled');
        $(".expirydate").hide();
        $("#cmspage_end_date").val(null);
        $('#cmspage_end_date').val('');
        $('.expirydate').next('span.help-block').html('');
        $('.expirydate').parent('.form-group').removeClass('has-error');
    } else {
        $('.expdatelabel').addClass('no_expiry');
        $('.expiry_lbl').text('No Expiry');
        $('#cmspage_end_date').attr('data-exp', '0');
        $('#cmspage_end_date').removeAttr('disabled');
        $(".expirydate").show();
        if ($('#cmspage_end_date').attr('data-newvalue').length > 0) {
            $("#cmspage_end_date").val($('#cmspage_end_date').attr('data-newvalue'));
        } else {
            $("#cmspage_end_date").val('');
        }
    }
});