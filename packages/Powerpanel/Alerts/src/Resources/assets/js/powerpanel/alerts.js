/**
 * This method show hides default banner fields
 * since   2016-12-22
 * author  NetQuick
 */
var Custom = function () {
    return {
        //main function
        init: function () {
            //initialize here something.            
        },
        checkType: function () {
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
        getModuleRecords: function (moduleName, modelName) {
            var ajaxUrl = site_url + '/powerpanel/alerts/selectRecords';
            jQuery.ajax({
                type: "POST",
                url: ajaxUrl,
                dataType: 'HTML',
                data: {"module": moduleName, "model": modelName, 'selected': selectedRecord},
                async: false,
                success: function (result) {
                    $('#foritem').html(result).select2({
                        placeholder: "Select Module",
                        width: '100%',
                        minimumResultsForSearch: 5
                    });
                }
            });
        }
    }
}();
/**
 * This method validates banner form fields
 * since   2016-12-24
 * author  NetQuick
 */
var Validate = function () {
    var handleAlerts = function () {
        $("#frmAlerts").validate({
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
                    daterange: true,
                    required: {
                        depends: function () {
                            var isChecked = $('#end_date_time').attr('data-exp');
                            if (isChecked == 0) {
                                return $('input[name=end_date_time]').val().length == 0;
                            }
                        }
                    }
                },
                ext_Link: {
                    required: {
                        depends: function () {
                            return ($('input:radio[name="link_type"][value="external"]').is(":checked")) ? true : false;
                        }
                    },
                    url: true,
                },
                display_order: {
                    required: true,
                    minStrict: true,
                    number: true,
                    noSpace: true,
                    xssProtection: true,
                    no_url: true
                },
                modules: {required: true, noSpace: true},
                foritem: {required: true, noSpace: true},
            },
            messages: {
                title:{required: Lang.get('validation.required', {attribute: Lang.get('template.title')})},
                modules: Lang.get('validation.required', {attribute: Lang.get('template.module')}),
                foritem: Lang.get('validation.required', {attribute: Lang.get('template.page')}),
                display_order: {
                    required: Lang.get('validation.required', {attribute: Lang.get('template.displayorder')}),
                    minStrict: Lang.get('validation.minStrict', {attribute: Lang.get('template.displayorder')}),
                    number: Lang.get('validation.number', {attribute: Lang.get('template.displayorder')}),
                    noSpace: Lang.get('validation.noSpace', {attribute: Lang.get('template.displayorder')})
                },
                ext_Link: {
                    required: "External Link is required.",
                },
                start_date_time: {
                    required: "Start date field is required.",
                },
                end_date_time: {
                    required: Lang.get('validation.required', {attribute: Lang.get('template.enddate')}),
                    daterange: 'The end date must be a greater than start date.'
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
                $('.alert-danger', $('#frmAlerts')).show();
            },
            highlight: function (element) { // hightlight error inputs
                $(element).closest('.form-group').addClass('has-error'); // set error class to the control group
            },
            unhighlight: function (element) {
                $(element).closest('.form-group').removeClass('has-error'); // set error class to the control group
            },
            submitHandler: function (form) { // for demo
                $('body').loader(loaderConfig);
                form.submit();
                $("button[type='submit']").attr('disabled','disabled');
                return false;
            }
        });
        $('#frmAlerts input').keypress(function (e) {
            if (e.which == 13) {
                if ($('#frmAlerts').validate().form()) {
                    $('#frmAlerts').submit(); //form validation success, call ajax form submit
                }
                return false;
            }
        });
    }
    return {
        //main function to initiate the module
        init: function () {
            handleAlerts();
        }
    };
}();
jQuery(document).ready(function () {
    Custom.init();
    Custom.checkType();
    Validate.init();

    $(document).on('click', '.banner', function (e) {
        Custom.checkType();
    });

    $('#modules').on("change", function (e) {
        Custom.getModuleRecords($("#modules option:selected").data('module'), $("#modules option:selected").data('model'));
    });

    jQuery.validator.addMethod("noSpace", function (value, element) {
        if (value.trim().length <= 0) {
            return false;
        } else {
            return true;
        }
    }, "This field is required");

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
jQuery.validator.addMethod("phoneFormat", function (value, element) {
    // allow any non-whitespace characters as the host part
    return this.optional(element) || /((\(\d{3}\) ?)|(\d{3}-))?\d{3}-\d{4}/.test(value);
}, 'Please enter a valid phone number.');

jQuery.validator.addMethod("minStrict", function (value, element) {
    // allow any non-whitespace characters as the host part
    if (value > 0) {
        return true;
    } else {
        return false;
    }
}, 'Display order must be a number higher than zero');

jQuery.validator.addMethod("noSpace", function (value, element) {
    if (value.trim().length <= 0) {
        return false;
    } else {
        return true;
    }
}, "This field is required");
$('input[name=title]').change(function () {
    var title = $(this).val();
    var trim_title = title.trim();
    if (trim_title) {
        $(this).val(trim_title);
        return true;
    }
});

jQuery(document).ready(function () {
    var radioValue = $("input[name='link_type']:checked").val();
    if (selectedRecord > 0) {
        $('#modules').trigger('change');
        if (radioValue == 'external')
        {
            $('#modules').select2({
                placeholder: "Select Module",
                width: '100%',
                minimumResultsForSearch: 5
            });
            $('#records').hide();
        }
    }
});

jQuery.validator.addMethod("daterange", function (value, element) {
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

$('.fromButton').click(function () {
    $('#start_date_time').datetimepicker('show');
});
$('.toButton').click(function () {
    $('#end_date_time').datetimepicker('show');
});

$(document).on("change", '#end_date_time', function () {
    $(this).attr('data-newvalue', $(this).val());
});

$('#noexpiry').click(function () {
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


function KeycheckOnlyPhonenumber(e) {
    var t = 0;
    t = document.all ? 3 : document.getElementById ? 1 : document.layers ? 2 : 0;
    if (document.all)
        e = window.event;
    var n = "";
    var r = "";
    if (t == 2) {
        if (e.which > 0)
            n = "(" + String.fromCharCode(e.which) + ")";
        r = e.which
    } else {
        if (t == 3) {
            r = window.event ? event.keyCode : e.which
        } else {
            if (e.charCode > 0)
                n = "(" + String.fromCharCode(e.charCode) + ")";
            r = e.charCode
        }
    }
    if (r >= 65 && r <= 90 || r >= 97 && r <= 122 || r >= 33 && r <= 39 || r >= 42 && r <= 42 || r >= 44 && r <= 44 || r >= 46 && r <= 47 || r >= 58 && r <= 64 || r >= 91 && r <= 96 || r >= 123 && r <= 126) {
        return false
    }
    return true
}
$.validator.addMethod("emailFormat", function (value, element) {
    // allow any non-whitespace characters as the host part
    return this.optional(element) || /^[_A-Za-z0-9-]+(\.[_A-Za-z0-9-]+)*@[A-Za-z0-9-]+(\.[A-Za-z0-9-]+)*(\.[A-Za-z]{2,4})$/.test(value);
}, 'Enter valid email format');
$.validator.addMethod("xssProtection", function (value, element) {
    // allow any non-whitespace characters as the host part
    return this.optional(element) || /<(\w+)((?:\s+\w+(?:\s*=\s*(?:(?:"[^"]*")|(?:'[^']*')|[^>\s]+))?)*)\s*(\/?)>/.test(value) == false ? true : false;
}, 'Enter valid input');
$.validator.addMethod("check_special_char", function (value, element) {
    if (value != '') {
        if (value.match(/^[\x20-\x7E\n]+$/)) {
            return true;
        } else {
            return false;
        }
    } else {
        return true;
    }
}, 'Please enter valid input');
$.validator.addMethod('no_url', function (value, element) {
    var re = /^[a-zA-Z0-9\-\.\:\\]+\.(com|org|net|mil|edu|COM|ORG|NET|MIL|EDU)$/;
    var re1 = /(ftp|http|https):\/\/(\w+:{0,1}\w*@)?(\S+)(:[0-9]+)?(\/|\/([\w#!:.?+=&%@!\-\/]))?/;
    var trimmed = $.trim(value);
    if (trimmed == '') {
        return true;
    }
    if (trimmed.match(re) == null && re1.test(trimmed) == false) {
        return true;
    }
}, "URL doesn't allowed");

$.validator.addMethod('validUrl', function (value, element) {
    var url = $.validator.methods.url.bind(this);
    return url(value, element) || url('http://' + value, element);
}, 'Please enter a valid URL');

$.validator.addMethod("phonenumber", function (value, element) {
    var numberPattern = /\d+/g;
    var newVal = value.replace(/\D/g);
    if (parseInt(newVal) <= 0) {
        return false;
    } else {
        return true;
    }
}, 'Please enter a valid phone number.');
$.validator.addMethod("noSpace", function (value, element) {
    if (value.trim().length <= 0) {
        return false;
    } else {
        return true;
    }
}, "No space please don't leave it empty");

$.validator.addMethod("alphanumeric", function (value, element) {
    return this.optional(element) || /^[\w.]+$/i.test(value);
}, "Letters, numbers, and underscores only please");

$.validator.addMethod("lettersonly", function (value, element) {
    return this.optional(element) || /^[a-z]+$/i.test(value);
}, "Letters only please");

$("#varPhoneNo").bind("paste", function (e) {
    // access the clipboard using the api
    return false;
});
var blacklist = /\b(nude|naked|sex|porn|porno|sperm|penis|pussy|vegina|boobs|asshole|bitch|dick)\b/;
jQuery.validator.addMethod("badwordcheck", function (value) {
    return !blacklist.test(value.toLowerCase());
}, "Please remove bad word/inappropriate language.");

$.validator.addMethod("languageTest", function (value) {
    regEx = /^[a-zA-Z0-9\-\+\"\(\)\'\:\$\%\<\>\@\!\#\&\*\,\=\{\}\.\/\;\[\]\^\_\s]+$/;
    if (value != '') {
        if (!regEx.test(value))
            return false;
        else if (regEx.test(value))
            return true;
    } else {
        return true;
    }
}, "Please enter valid input.");

$.validator.addMethod("emailFormat", function (value, element) {
    // allow any non-whitespace characters as the host part
    return this.optional(element) || /^[_A-Za-z0-9-]+(\.[_A-Za-z0-9-]+)*@[A-Za-z0-9-]+(\.[A-Za-z0-9-]+)*(\.[A-Za-z]{2,4})$/.test(value);
}, 'Enter valid email format.');

$.validator.addMethod("phonenumber_mobile", function (value, element) {
    return (value.match(/^[0-9-_ +()]+$/i));
}, 'Please enter valid phone number.');

$.validator.addMethod("phonenumber", function (value, element) {
    var numberPattern = /\d+/g;
    var newVal = value.replace(/\D/g);
    if (parseInt(newVal) <= 0) {
        return false;
    } else {
        return true;
    }
}, 'Please enter a valid phone number.');