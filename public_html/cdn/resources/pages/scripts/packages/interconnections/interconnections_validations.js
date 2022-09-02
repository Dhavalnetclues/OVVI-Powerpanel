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
              ignore: [],
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
                doc_id: {
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
                    required: "Publish date field is required.",
                },
                doc_id: {
                    required: "Document field is required.",
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
    var checkcatvalue = $("#parent_category_id").val();
    if (checkcatvalue > 0) {
        $('#pubdate').show();
        $('#docHide').show();
        $("#interconnection_date").rules('add', {
            required: true
        });
        $("input[name='doc_id']").rules('add', {
            required: true
        });
    } else {
        $('#pubdate').hide();
        $('#docHide').hide();
    }
    $('#parent_category_id').on("change", function (e) {
        var catvalue = $("#parent_category_id option:selected").val();
        if (catvalue != null && catvalue != 'undefined' && catvalue > 0) {
            $('#pubdate').show();
            $('#docHide').show();
            $("#interconnection_date").rules('add', {
                required: true
            });
            
            $("input[name='doc_id']").rules('add', {
                required: true
            });
        } else {
            $('#pubdate').hide();
            $('#docHide').hide();
        }

    });
    jQuery.validator.addMethod("noSpace", function(value, element) {
        if (value.trim().length <= 0) {
            return false;
        } else {
            return true;
        }
    }, "This field is required");

    $('#interconnection_date').datetimepicker({
         format: DEFAULT_DATE_FORMAT,
        autoclose: true,
        timepicker: false,
        minuteStep: 5,
        onShow: function () {
            this.setOptions({})
        },
        startdate: true,
        scrollMonth: false,
        scrollInput: false
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