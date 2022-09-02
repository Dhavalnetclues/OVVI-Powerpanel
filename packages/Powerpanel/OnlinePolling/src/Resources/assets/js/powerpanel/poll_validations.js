/**
 * This method validates FAQs form fields
 * since   2016-12-24
 * author  NetQuick
 */
var Validate = function() {
    var handleonlinepolling = function() {
        $("#frmonlinepolling").validate({
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
                'question[0][question]': {
                    required: true,
                    noSpace: true,
                    xssProtection: true,
                    no_url: true
                },
                'question[0][options][]': {
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
                            var isChecked = $('#poll_end_date').attr('data-exp');
                            if (isChecked == 0) {
                                return $('input[name=end_date_time]').val().length == 0;
                            }
                        }
                    }
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
                title: { required: Lang.get('validation.required', { attribute: Lang.get('template.title') }) },
                category_id: "Please select category.",
                display_order: { required: Lang.get('validation.required', { attribute: Lang.get('template.displayorder') }) },
                end_date_time: Lang.get('validation.required', { attribute: Lang.get('template.enddate') }),
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
                $('.alert-danger', $('#frmonlinepolling')).show();
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
        $('#frmonlinepolling input').keypress(function(e) {
            if (e.which == 13) {
                if ($('#frmonlinepolling').validate().form()) {
                    $('#frmonlinepolling').submit();
                }
                return false;
            }
        });
    }
    return {
        init: function() {
            handleonlinepolling();
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


    var isChecked = $('#poll_end_date').attr('data-exp');
    if (isChecked == 1) {
        $('.expdatelabel').removeClass('no_expiry');
        $('.expiry_lbl').text('Set Expiry');
        $(".expirydate").hide();
        $('#poll_end_date').attr('disabled', 'disabled');
    } else {
        $('.expdatelabel').addClass('no_expiry');
        $('.expiry_lbl').text('No Expiry');
        $('#poll_end_date').removeAttr('disabled');
    }
});
jQuery(document).ready(function() {
    $('#poll_start_date').datetimepicker({
        format: DEFAULT_DATE_FORMAT + ' ' + DEFAULT_TIME_FORMAT,
        onShow: function() {
            this.setOptions({})
        },
        scrollMonth: false,
        scrollInput: false
    });

    $('#poll_end_date').datetimepicker({
        format: DEFAULT_DATE_FORMAT + ' ' + DEFAULT_TIME_FORMAT,
        onShow: function() {
            this.setOptions({})
        },
        scrollMonth: false,
        scrollInput: false
    });
});

$('.fromButton').click(function() {
    $('#poll_start_date').datepicker('show');
});
$('.toButton').click(function() {
    $('#poll_end_date').datepicker('show');
});

$(document).on("change", '#poll_end_date', function() {
    $(this).attr('data-newvalue', $(this).val());
});

$('#noexpiry').click(function() {
    var isChecked = $('#poll_end_date').attr('data-exp');

    if (isChecked == 0) {
        $('.expdatelabel').removeClass('no_expiry');
        $('.expiry_lbl').text('Set Expiry');
        $('#poll_end_date').attr('data-exp', '1');
        $('#poll_end_date').attr('disabled', 'disabled');
        $(".expirydate").hide();
        $("#poll_end_date").val(null);
        $('#poll_end_date').val('');
        $('.expirydate').next('span.help-block').html('');
        $('.expirydate').parent('.form-group').removeClass('has-error');
    } else {
        $('.expdatelabel').addClass('no_expiry');
        $('.expiry_lbl').text('No Expiry');
        $('#poll_end_date').attr('data-exp', '0');
        $('#poll_end_date').removeAttr('disabled');
        $(".expirydate").show();
        if ($('#poll_end_date').attr('data-newvalue').length > 0) {
            $("#poll_end_date").val($('#poll_end_date').attr('data-newvalue'));
        } else {
            $("#poll_end_date").val('');
        }
    }
});

$(document).on('click', '#add_question', function(e) {

    var question_length = $('.question').length;

    var question_html = '';

    question_html = question_html + '<div class="question" id="que_' + question_length + '">';
    question_html = question_html + '<div class="col-md-8">';
    question_html = question_html + '<div class="form-group form-md-line-input">';
    question_html = question_html + '<label class="form_title">Question <span aria-required="true" class="required"> * </span></label>';
    question_html = question_html + "<input id='q_" + question_length + "' maxlength='150' placeholder='Enter your question' class='form-control maxlength-handler' autocomplete='off' name='question[" + question_length + "][question]' type='text'>";
    question_html = question_html + '<span class="help-block"></span>';
    question_html = question_html + '</div>';
    question_html = question_html + '</div>';
    question_html = question_html + '<div class="col-md-2">';
    question_html = question_html + '<div class="form-group form-md-line-input">';
    question_html = question_html + '<label class="form_title">Answer Choice <span aria-required="true" class="required"> * </span></label>';
    question_html = question_html + "<select id='q_c_" + question_length + "' class='form-control answer_choice' data-id=" + question_length + " name='question[" + question_length + "][question_choice]'><option value='TX'>Single Textbox</option><option value='CB'>Checkbox</option><option value='RD'>Radio</option></select>";
    question_html = question_html + '<span class="help-block"></span>';
    question_html = question_html + '</div>';
    question_html = question_html + '</div>';
    question_html = question_html + '<div class="col-md-2">';
    question_html = question_html + '<button type="button" data-id="' + question_length + '" class="btn btn-green-drake" id="remove_question" style="margin-top:20px;">Remove</a>';
    question_html = question_html + '</div>';

    question_html = question_html + '<div id="answer_choice_' + question_length + '">';
    question_html = question_html + '</div>';

    question_html = question_html + '</div>';

    $('#question_list').append(question_html);

});


$(document).on('change', '.answer_choice', function(e) {

    var id = $(this).data('id');
    var value = $(this).val();
    if (value != 'TX') {
        var opt_length = $('.choice_option').length;
        var opt_html = '';

        opt_html = opt_html + '<div class="choice_option" id="choice_opt_' + opt_length + '">';
        opt_html = opt_html + '<div class="col-md-10">';
        opt_html = opt_html + '<div class="form-group form-md-line-input">';
        opt_html = opt_html + "<input id='opt_" + opt_length + "' maxlength='150' placeholder='Enter your answer' class='form-control maxlength-handler' autocomplete='off' name='question[" + id + "][options][]' type='text'>";
        opt_html = opt_html + '<span class="help-block"></span>';
        opt_html = opt_html + '</div>';
        opt_html = opt_html + '</div>';
        opt_html = opt_html + '<div class="col-md-2">';
        opt_html = opt_html + '<button type="button" data-id="' + id + '" class="btn add_option"><i class="fa fa-plus" /></a>';
        opt_html = opt_html + '<button type="button" data-id="' + opt_length + '" class="btn remove_option"><i class="fa fa-minus" /></a>';
        opt_html = opt_html + '</div>';

        $('#answer_choice_' + id).html(opt_html);

    } else {
        $('#answer_choice_' + id).html(opt_html);
    }



});

$(document).on('click', '.add_option', function(e) {

    var id = $(this).data('id');

    var opt_length = $('.choice_option').length;

    var opt_html = '';
    opt_html = opt_html + '<div class="choice_option" id="choice_opt_' + opt_length + '">';
    opt_html = opt_html + '<div class="col-md-10">';
    opt_html = opt_html + '<div class="form-group form-md-line-input">';
    opt_html = opt_html + "<input id='opt_" + opt_length + "' maxlength='150' placeholder='Enter your answer' class='form-control maxlength-handler' autocomplete='off' name='question[" + id + "][options][]' type='text'>";
    opt_html = opt_html + '<span class="help-block"></span>';
    opt_html = opt_html + '</div>';
    opt_html = opt_html + '</div>';
    opt_html = opt_html + '<div class="col-md-2">';
    opt_html = opt_html + '<button type="button" data-id="' + opt_length + '" class="btn remove_option"><i class="fa fa-minus" /></a>';
    opt_html = opt_html + '</div>';

    $('#answer_choice_' + id).append(opt_html);

});

$(document).on('click', '.remove_option', function(e) {
    var id = $(this).data('id');
    $('#choice_opt_' + id).remove();
});

$(document).on('click', '#remove_question', function(e) {
    var id = $(this).data('id');
    $('#que_' + id).remove();
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
$('input[type=text]').change(function() {
    var input = $(this).val();
    var trim_input = input.trim();
    if (trim_input) {
        $(this).val(trim_input);
        return true;
    }
});

jQuery.validator.addMethod("daterange", function(value, element) {
    var fromDateTime = $('#poll_start_date').val();
    var toDateTime = $("#poll_end_date").val();
    var isChecked = $('#poll_end_date').attr('data-exp');
    if (isChecked == 0) {
        toDateTime = new Date(toDateTime);
        fromDateTime = new Date(fromDateTime);
        return toDateTime >= fromDateTime && fromDateTime <= toDateTime;
    } else {
        return true;
    }
}, "The End date must be a date after start date.");