/**
 * This method validates team form fields
 * since   2016-12-24
 * author  NetQuick
 */
var Validate = function() {
    var handleTeam = function() {
        $("#frmcomplaintservices").validate({
            errorElement: 'span', //default input error message container
            errorClass: 'help-block', // default input error message class
            ignore: [],
            rules: {
                title: {
                    required: true,
                    noSpace: true
                },
                email: {
                    required: true,
                    multiemails: true
                },
                img_id: {
                    required: true
                },
                order: {
                    required: true,
                    minStrict: true,
                    number: true,
                    noSpace: true
                },
            },
            messages: {
                title: 'Title Field is required',
                img_id: {
                    required: 'Image field is required.'
                },
                email: {
                    required: "Email Field is required",
                    multiemails: "multiplemail format is not correct"
                },

                order: {
                    required: Lang.get('validation.required', {
                        attribute: Lang.get('template.displayorder')
                    })
                }
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
                $('.alert-danger', $('#frmcomplaintservices')).show();
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
        $('#frmcomplaintservices input').on('keypress', function(e) {
            if (e.which == 13 && e.keyCode != 188) {
                if ($('#frmcomplaintservices').validate().form()) {
                    $('#frmcomplaintservices').submit(); //form validation success, call ajax form submit
                    $("button[type='submit']").attr('disabled', 'disabled');
                }
                return false;
            }
        });
    }
    return {
        //main function to initiate the module
        init: function() {
            handleTeam();
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
    }, "Space is not allowed.");

    jQuery.validator.addMethod("minStrict", function(value, element) {
        // allow any non-whitespace characters as the host part
        if (value > 0) {
            return true;
        } else {
            return false;
        }
    }, 'Display order must be a number higher than zero');

    jQuery.validator.addMethod(
        "multiemails",
        function(value, element) {
            if (this.optional(element)) // return true on optional element
                return true;
            var email = value.split(/[,]+/); // split element by , and ;
            valid = true;
            for (var i in email) {
                value = email[i];
                valid = valid &&
                    jQuery.validator.methods.email.call(this, $.trim(value), element);
            }
            return valid;
        },
        jQuery.validator.messages.multiemails
    );

});

$('input[type=text]').on('change', function() {
    var input = $(this).val();
    var trim_input = input.trim();
    if (trim_input) {
        $(this).val(trim_input);
        return true;
    }
});
/*********** Remove Image code start Here  *************/
$(document).ready(function() {
    if ($("input[name='img_id']").val() == '') {
        $('.removeimg').hide();
        $('.image_thumb .overflow_layer').css('display', 'none');
    } else {
        $('.removeimg').show();
        $('.image_thumb .overflow_layer').css('display', 'block');
    }
    $(document).on('click', '.removeimg', function(e) {
        $("input[name='img_id']").val('');
        $("input[name='image_url']").val('');
        $(".fileinput-new div img").attr("src", site_url + "/resources/images/upload_file.gif");
        if ($("input[name='img_id']").val() == '') {
            $('.removeimg').hide();
            $('.image_thumb .overflow_layer').css('display', 'none');
        } else {
            $('.removeimg').show();
            $('.image_thumb .overflow_layer').css('display', 'block');
        }
    });
});
/************** Remove Images Code end ****************/