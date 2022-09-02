var Custom = function() {
    return {
        //main function
        init: function() {
            //initialize here something.            
        },
        checkType: function() {
            var radioValue = $("input[name='link_type']:checked").val();
            if (radioValue == 'internal') {
                $('#ext_Link_div').hide();
                $('#pages').show();
                if ($('#modules').val() != ' ') {
                    $('#records').show();
                }
                $('#inner_recommandation').show();
                $('#home_recommandation').hide();
            } else if (radioValue == 'external') {
                $('#ext_Link_div').show();
                $('#pages').hide();
                $('#records').hide();
                $('#home_recommandation').show();
                $('#inner_recommandation').hide();
            }
            $('#modules').select2({
                placeholder: "Select Module",
                width: '100%',
                minimumResultsForSearch: 5
            });
        },
        getModuleRecords: function(moduleName, modelName) {
            var ajaxUrl = site_url + '/powerpanel/links/selectRecords';
            jQuery.ajax({
                type: "POST",
                url: ajaxUrl,
                dataType: 'HTML',
                data: { "module": moduleName, "model": modelName, 'selected': selectedRecord },
                async: false,
                success: function(result) {
                    $('#foritem').html(result).select2({
                        placeholder: "Select Module",
                        width: '100%',
                        minimumResultsForSearch: 5
                    });
                }
            });
        },
        getSectorRecords: function(sectorName) {

            var ajaxUrl = site_url + '/powerpanel/links/getCategory';
            jQuery.ajax({
                type: "POST",
                url: ajaxUrl,
                dataType: 'HTML',
                data: { "sectorname": sectorName, "selectedCategory": selectedCategory },
                async: false,
                success: function(result) {
                    $("#category_id").html(result).trigger('change.select2');
                }
            });
        }

    }
}();
var Validate = function() {
    var handleLinks = function() {
        $("#frmLinks").validate({
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
                order: {
                    required: true,
                    minStrict: true,
                    number: true,
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
                            var isChecked = $('#links_end_date').attr('data-exp');
                            if (isChecked == 0) {
                                return $('input[name=end_date_time]').val().length == 0;
                            }
                        }
                    }
                },
                ext_Link: {
                    required: {
                        depends: function() {
                            return ($('input:radio[name="link_type"][value="external"]').is(":checked")) ? true : false;
                        }
                    },
                    url: true,
                },
                modules: {
                    required: {
                        depends: function() {
                            return $('#internal_linktype[value="internal"]:checked').length > 0;
                        }
                    },
                    noSpace: {
                        depends: function() {
                            return $('#internal_linktype[value="internal"]:checked').length > 0;
                        }
                    }
                },
                foritem: {
                    required: {
                        depends: function() {
                            return $('#internal_linktype[value="internal"]:checked').length > 0;
                        }
                    },
                    noSpace: {
                        depends: function() {
                            return $('#internal_linktype[value="internal"]:checked').length > 0;
                        }
                    }
                }

            },
            messages: {
                title: {
                    required: Lang.get('validation.required', { attribute: Lang.get('template.title') })
                },
                sector: {
                    required: "Sector field is required.",
                },
                category: "Please select category.",
                modules: Lang.get('validation.required', { attribute: Lang.get('template.module') }),
                foritem: Lang.get('validation.required', { attribute: Lang.get('template.page') }),
                ext_Link: {
                    required: "External Link is required.",
                },
                order: {
                    required: Lang.get('validation.required', { attribute: Lang.get('template.displayorder') })
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
                $('.alert-danger', $('#frmLinks')).show();
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
        $('#frmLinks input').keypress(function(e) {
            if (e.which == 13) {
                if ($('#frmLinks').validate().form()) {
                    $('#frmLinks').submit();
                }
                return false;
            }
        });
    }
    return {
        init: function() {
            handleLinks();
        }
    };
}();
jQuery(document).ready(function() {
    Custom.init();
    Custom.checkType();
    Validate.init();

    $(document).on('click', '.banner', function(e) {
        Custom.checkType();
    });

    $('#modules').on("change", function(e) {
        Custom.getModuleRecords($("#modules option:selected").data('module'), $("#modules option:selected").data('model'));
    });

    $('#varSector').on("change", function(e) {
        Custom.getSectorRecords($("#varSector option:selected").val());
    });


    $(window).load(function() {
        if (selectedCategory > 0) {
            $('#varSector').trigger('change');

        }
        var radioValue = $("input[name='link_type']:checked").val();
        if (selectedRecord > 0) {
            $('#modules').trigger('change');
            if (radioValue == 'external') {
                $('#modules').select2({
                    placeholder: "Select Module",
                    width: '100%',
                    minimumResultsForSearch: 5
                });
                $('#records').hide();
            }
        }
    });
    jQuery.validator.addMethod("noSpace", function(value, element) {
        if (value.trim().length <= 0) {
            return false;
        } else {
            return true;
        }
    }, "This field is required");

    var isChecked = $('#links_end_date').attr('data-exp');
    if (isChecked == 1) {
        $('.expdatelabel').removeClass('no_expiry');
        $('.expiry_lbl').text('Set Expiry');
        $(".expirydate").hide();
        $('#links_end_date').attr('disabled', 'disabled');
    } else {
        $('.expdatelabel').addClass('no_expiry');
        $('.expiry_lbl').text('No Expiry');
        $('#links_end_date').removeAttr('disabled');
    }

});
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
$('input[type=text]').change(function() {
    var input = $(this).val();
    var trim_input = input.trim();
    if (trim_input) {
        $(this).val(trim_input);
        return true;
    }
});

jQuery.validator.addMethod("daterange", function(value, element) {
    var fromDateTime = $('#links_start_date').val();
    var toDateTime = $("#links_end_date").val();
    var isChecked = $('#links_end_date').attr('data-exp');
    if (isChecked == 0) {
        toDateTime = new Date(toDateTime);
        fromDateTime = new Date(fromDateTime);
        return toDateTime >= fromDateTime && fromDateTime <= toDateTime;
    } else {
        return true;
    }
}, "The end date must be a greater than start date.");

$('.fromButton').click(function() {
    $('#links_start_date').datetimepicker('show');
});
$('.toButton').click(function() {
    $('#links_end_date').datetimepicker('show');
});

$(document).on("change", '#links_end_date', function() {
    $(this).attr('data-newvalue', $(this).val());
});

$('#noexpiry').click(function() {
    var isChecked = $('#links_end_date').attr('data-exp');

    if (isChecked == 0) {
        $('.expdatelabel').removeClass('no_expiry');
        $('.expiry_lbl').text('Set Expiry');
        $('#links_end_date').attr('data-exp', '1');
        $('#links_end_date').attr('disabled', 'disabled');
        $(".expirydate").hide();
        $("#links_end_date").val(null);
        $('#links_end_date').val('');
        $('.expirydate').next('span.help-block').html('');
        $('.expirydate').parent('.form-group').removeClass('has-error');
    } else {
        $('.expdatelabel').addClass('no_expiry');
        $('.expiry_lbl').text('No Expiry');
        $('#links_end_date').attr('data-exp', '0');
        $('#links_end_date').removeAttr('disabled');
        $(".expirydate").show();
        if ($('#links_end_date').attr('data-newvalue').length > 0) {
            $("#links_end_date").val($('#links_end_date').attr('data-newvalue'));
        } else {
            $("#links_end_date").val('');
        }
    }
});