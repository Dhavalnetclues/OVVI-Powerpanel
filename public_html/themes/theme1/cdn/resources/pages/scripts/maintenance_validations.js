/**
 * This method validates FAQs form fields
 * since   2016-12-24
 * author  NetQuick
 */
var Validate = function () {
    var handlemaintenance = function () {
        $("#frmmaintenance").validate({
            errorElement: 'span',
            errorClass: 'help-block',
            ignore: [],
            rules: {
                emailsubject: {
                    required: true,
                    noSpace: true,
                    xssProtection: true,
                    no_url: true
                },
                start_date_time: {
                    required: true,
                    noSpace: true
                },
                total: {
                    time: true,
                    number:true
                },
                short_description:{
                  xssProtection: true,
                  no_url: true  
                },
                end_date_time: {
                    required: true,
                    noSpace: true
                },
                display_order: {
                    required: true,
                    minStrict: true,
                    number: true,
                    noSpace: true,
                    xssProtection: true,
                    no_url: true
                },
            },
            messages: {
                emailsubject: {required:'Email subject field is required.'},
                start_date_time: 'Starting date field is required.',
                end_date_time: 'Compilation date field is required.',
                display_order: {required: Lang.get('validation.required', {attribute: Lang.get('template.displayorder')})},
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
            invalidHandler: function (event, validator) {
                var errors = validator.numberOfInvalids();
                if (errors) {
                    $.loader.close(true);
                }
                $('.alert-danger', $('#frmmaintenance')).show();
            },
            highlight: function (element) {
                $(element).closest('.form-group').addClass('has-error');
            },
            unhighlight: function (element) {
                $(element).closest('.form-group').removeClass('has-error');
            },
            submitHandler: function (form) {
                $('body').loader(loaderConfig);
                form.submit();
                return false;
            }
        });
        $('#frmmaintenance input').keypress(function (e) {
            if (e.which == 13) {
                if ($('#frmmaintenance').validate().form()) {
                    $('#frmmaintenance').submit();
                }
                return false;
            }
        });
    }
    return {
        init: function () {
            handlemaintenance();
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
jQuery.validator.addMethod("noSpace", function (value, element) {
    if (value.trim().length <= 0) {
        return false;
    } else {
        return true;
    }
}, "This field is required");
jQuery.validator.addMethod("minStrict", function (value, element) {
    if (value > 0) {
        return true;
    } else {
        return false;
    }
}, 'Display order must be a number higher than zero');
jQuery.validator.addMethod("time", function(value, element) {  
   if (/^(([0-9])|([0-9][0-9])):([0-9]?[0-9])(:([0-9]?[0-9]))?$/i.test(value)) {
        return true;
    } else {
        return true;
    }
}, "Please enter a valid hours. <br/><span style='color:blue'>Format: '00:00'</span>");
$('input[type=text]').change(function () {
    var input = $(this).val();
    var trim_input = input.trim();
    if (trim_input) {
        $(this).val(trim_input);
        return true;
    }
});
function KeycheckOnlyHour(e) {
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
    if (r >= 65 && r <= 90 || r >= 97 && r <= 122 || r >= 33 && r <= 41 || r >= 42 && r <= 43 || r >= 44 && r <= 46 || r >= 47 && r <= 47 || r >= 59 && r <= 64 || r >= 91 && r <= 96 || r >= 123 && r <= 126) {
        return false
    }
    return true
}
