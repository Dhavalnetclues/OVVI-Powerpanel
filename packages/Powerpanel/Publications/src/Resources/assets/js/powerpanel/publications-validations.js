/**
 * This method validates service form fields
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

            var ajaxUrl = site_url + '/powerpanel/publications/getCategory';
            jQuery.ajax({
                type: "POST",
                url: ajaxUrl,
                dataType: 'HTML',
                data: { "sectorname": sectorName, "selectedCategory": selectedCategory, "selectedId": selectedId },
                async: false,
                success: function(result) {
                    $("#category_id").html(result).trigger('change.select2');
                }
            });
        }
    }
}();
var Validate = function() {
    var handlePublications = function() {
        $("#frmPublications").validate({
            errorElement: 'span', //default input error message container
            errorClass: 'help-block', // default input error message class
            ignore: [],
            rules: {

                doc_id: {
                    required: true
                },
                title: {
                    required: true,
                    noSpace: true,
                    xssProtection: true,
                    no_url: true
                },
                sector: {
                    required: true
                },
                short_description: {
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
                            var isChecked = $('#end_date_time').attr('data-exp');
                            if (isChecked == 0) {
                                return $('input[name=end_date_time]').val().length == 0;
                            }
                        }
                    }
                },
                start_date: {
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
                    required: Lang.get('validation.required', {
                        attribute: Lang.get('template.title')
                    })
                },

                doc_id: {
                    required: 'Document field is required.'
                },
                sector: {
                    required: 'Sector field is required.'
                },
                start_date_time: {
                    required: "Start date field is required.",
                },
                end_date_time: {
                    required: Lang.get('validation.required', { attribute: Lang.get('template.enddate') }),
                    daterange: 'The end date must be a greater than start date.'
                },
                varMetaTitle: { required: Lang.get('validation.required', { attribute: Lang.get('template.metatitle') }) },
                varMetaDescription: { required: Lang.get('validation.required', { attribute: Lang.get('template.metadescription') }) },
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
                if (errors) {
                    $.loader.close(true);
                }
                $('.alert-danger', $('#frmPublications')).show();
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
        $('#frmPublications input').keypress(function(e) {
            if (e.which == 13 && e.keyCode != 188) {
                if ($('#frmPublications').validate().form()) {
                    $('#frmPublications').submit(); //form validation success, call ajax form submit
                }
                return false;
            }
        });
    }
    return {
        //main function to initiate the module
        init: function() {
            handlePublications();
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
        if (selectedCategory > 0 && selectedId > 0) {
            $('#varSector').trigger('change');
        }
    });

    $("input[id^='publication_date']").rules('add', {
        required: true
    });

    jQuery.validator.addMethod("noSpace", function(value, element) {
        if (value.trim().length <= 0) {
            return false;
        } else {
            return true;
        }
    }, "This field is required");

    jQuery.validator.addMethod("minStrict", function(value, element) {
        // allow any non-whitespace characters as the host part
        if (value > 0) {
            return true;
        } else {
            return false;
        }
    }, 'Display order must be a number higher than zero');


    var isChecked = $('#end_date_time').attr('data-exp');
    if (isChecked == 1) {
        $('.expdatelabel').removeClass('no_expiry');
        $('.expiry_lbl').text('Set Expiry');
        $(".expirydate").hide();
        $('#end_date_time').attr('disabled', 'disabled');
    } else {
        $('.expdatelabel').addClass('no_expiry');
        $('.expiry_lbl').text('No Expiry');
        $('#end_date_time').removeAttr('disabled');
    }

});
jQuery(document).ready(function() {
    $('#publication_date').datetimepicker({
        format: DEFAULT_DATE_FORMAT,
        autoclose: true,
        timepicker: false,
        minuteStep: 5,
        onShow: function() {
            this.setOptions({})
        },
        startdate: true,
        scrollMonth: false,
        scrollInput: false
    });
});

$('.fromButton').click(function() {
    $('#start_date_time').datetimepicker('show');
});
$('.toButton').click(function() {
    $('#end_date_time').datetimepicker('show');
});
$(document).on("change", '#end_date_time', function() {
    $(this).attr('data-newvalue', $(this).val());
});

jQuery.validator.addMethod("daterange", function(value, element) {
    var fromDateTime = $('#start_date_time').val();
    var toDateTime = $("#end_date_time").val();
    var isChecked = $('#end_date_time').attr('data-exp');
    if (isChecked == 0) {
        toDateTime = new Date(toDateTime);
        fromDateTime = new Date(fromDateTime);
        return toDateTime >= fromDateTime && fromDateTime <= toDateTime;
    } else {
        return true;
    }
}, "The end date must be a greater than start date.");


$('#noexpiry').click(function() {
    var isChecked = $('#end_date_time').attr('data-exp');

    if (isChecked == 0) {
        $('.expdatelabel').removeClass('no_expiry');
        $('.expiry_lbl').text('Set Expiry');
        $('#end_date_time').attr('data-exp', '1');
        $('#end_date_time').attr('disabled', 'disabled');
        $(".expirydate").hide();
        $("#end_date_time").val(null);
        $('#end_date_time').val('');
        $('.expirydate').next('span.help-block').html('');
        $('.expirydate').parent('.form-group').removeClass('has-error');
    } else {
        $('.expdatelabel').addClass('no_expiry');
        $('.expiry_lbl').text('No Expiry');
        $('#end_date_time').attr('data-exp', '0');
        $('#end_date_time').removeAttr('disabled');
        $(".expirydate").show();
        if ($('#end_date_time').attr('data-newvalue').length > 0) {
            $("#end_date_time").val($('#end_date_time').attr('data-newvalue'));
        } else {
            $("#end_date_time").val('');
        }
    }
});