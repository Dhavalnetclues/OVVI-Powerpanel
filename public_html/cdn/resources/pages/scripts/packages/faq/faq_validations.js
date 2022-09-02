/**
 * This method validates FAQs form fields
 * since   2016-12-24
 * author  NetQuick
 */
var Custom = function() {
    return {
        //main function
        init: function() {
            //initialize here something.
        },

        getModuleRecords: function(sectorName) {
            var ajaxUrl = site_url + '/powerpanel/faq/getCategory';
            jQuery.ajax({
                type: "POST",
                url: ajaxUrl,
                dataType: 'HTML',
                data: { "sectorname": sectorName, "selectedCategory": selectedCategory },
                async: false,
                beforeSend: function() {
                    choicesEl['category_id'].destroy();
                },
                success: function(result) {
                    $("#category_id").html(result).trigger('change');
                },
                complete:function(){
                    let element = document.getElementById('category_id');
                    const choices = new Choices(element);
                    choicesEl['category_id'] = choices;
                }
            });
        }
    }
}();
var Validate = function() {
    var handleCaionCatelogues = function() {
        $("#frmFaq").validate({
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
                category_id: {
                    required: true,
                    noSpace: true
                },
                description: {
                    required: true,
                },
                start_date_time: {
                    required: true,
                },
                end_date_time: {
                    daterange: true,
                    required: {
                        depends: function() {
                            var isChecked = $('#faq_end_date').attr('data-exp');
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
                }
            },
            messages: {
                title: {
                    required: Lang.get('validation.required', { attribute: Lang.get('template.title') })
                },
                category_id: "Please select category.",
                description: "Description field is required.",
                order: {
                    required: Lang.get('validation.required', { attribute: Lang.get('template.displayorder') })
                },
                sector: {
                    required: "Sector field is required.",
                },
                start_date_time: {
                    required: "Start date field is required.",
                },
                end_date_time: {
                    required: Lang.get('validation.required', { attribute: Lang.get('template.enddate') }),
                    daterange: 'The end date must be a greater than start date.'
                },
            },
            errorPlacement: function(error, element) {
                if (element.parent('.input-group').length) {
                    error.insertAfter(element.parent());
                } else if (element.hasClass('choices__input')) {
                    error.insertAfter(element.parent().parent().next('span'));
                } else if (element.attr('name') == 'description') {
                    error.insertAfter(element.next().next('span'));
                } else {
                    error.insertAfter(element);
                }
            },
            invalidHandler: function(event, validator) {
                var errors = validator.numberOfInvalids();
                if (errors) {
                    $.loader.close(true);
                }
                $('.alert-danger', $('#frmFaq')).show();
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
        $('#frmFaq input').keypress(function(e) {
            if (e.which == 13) {
                if ($('#frmFaq').validate().form()) {
                    $('#frmFaq').submit();
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
    Custom.init();

    $('#varSector').on("change", function(e) {
        Custom.getModuleRecords($("#varSector option:selected").val());
    });
    $(window).load(function() {

        if (selectedCategory > 0) {
            $('#varSector').trigger('change');

        }
    });
    jQuery.validator.addMethod("noSpace", function(value, element) {
        if (value.trim().length <= 0) {
            return false;
        } else {
            return true;
        }
    }, "This field is required");
    var isChecked = $('#faq_end_date').attr('data-exp');
    if (isChecked == 1) {
        $('.expdatelabel').removeClass('no_expiry');
        $('.expiry_lbl').text('Set Expiry');
        $(".expirydate").hide();
        $('#faq_end_date').attr('disabled', 'disabled');
    } else {
        $('.expdatelabel').addClass('no_expiry');
        $('.expiry_lbl').text('No Expiry');
        $('#faq_end_date').removeAttr('disabled');
    }
});

// $('.fromButton').click(function() {
//     $('#faq_start_date').datetimepicker('show');
// });
// $('.toButton').click(function() {
//     $('#faq_end_date').datetimepicker('show');
// });
$(document).on("change", '#faq_end_date', function() {
    $(this).attr('data-newvalue', $(this).val());
});
// jQuery(document).ready(function() {
//     $('#faq_start_date').datetimepicker({
//         format: DEFAULT_DATE_FORMAT + ' ' + DEFAULT_TIME_FORMAT,
//         onShow: function() {
//             this.setOptions({})
//         },
//         scrollMonth: false,
//         scrollInput: false
//     });

//     $('#faq_end_date').datetimepicker({
//         format: DEFAULT_DATE_FORMAT + ' ' + DEFAULT_TIME_FORMAT,
//         onShow: function() {
//             this.setOptions({})
//         },
//         scrollMonth: false,
//         scrollInput: false
//     });
// });

jQuery.validator.addMethod("daterange", function(value, element) {
    var fromDateTime = $('#faq_start_date').val();
    var toDateTime = $("#faq_end_date").val();
    var isChecked = $('#faq_end_date').attr('data-exp');
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

$('#noexpiry').click(function() {
    var isChecked = $('#faq_end_date').attr('data-exp');

    if (isChecked == 0) {
        $('.expdatelabel').removeClass('no_expiry');
        $('.expiry_lbl').text('Set Expiry');
        $('#faq_end_date').attr('data-exp', '1');
        $('#faq_end_date').attr('disabled', 'disabled');
        $(".expirydate").hide();
        $("#faq_end_date").val(null);
        $('#faq_end_date').val('');
        $('.expirydate').next('span.help-block').html('');
        $('.expirydate').parent('.form-group').removeClass('has-error');
    } else {
        $('.expdatelabel').addClass('no_expiry');
        $('.expiry_lbl').text('No Expiry');
        $('#faq_end_date').attr('data-exp', '0');
        $('#faq_end_date').removeAttr('disabled');
        $(".expirydate").show();
        if ($('#faq_end_date').attr('data-newvalue').length > 0) {
            $("#faq_end_date").val($('#faq_end_date').attr('data-newvalue'));
        } else {
            $("#faq_end_date").val('');
        }
    }
});

$('#faq_start_date').flatpickr({
    dateFormat: DEFAULT_DATE_FORMAT + " H:i",
    minDate: 'today',
    enableTime: true
});
$('#faq_end_date').flatpickr({
    dateFormat: DEFAULT_DATE_FORMAT + " H:i",
    minDate: 'today',
    enableTime: true
});
$('#faq_start_date').on('change', function (e) {
    let index = e.target.getAttribute("data-dateIndex");
    let sdate = new Date(e.target.value)
    $('#faq_end_date').flatpickr({
        dateFormat: DEFAULT_DATE_FORMAT + " H:i",
        minDate: sdate,
    enableTime: true
    }).clear();
});