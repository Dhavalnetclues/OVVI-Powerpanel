/**
 * This method validates banner form fields
 * since   2016-12-24
 * author  NetQuick
 */
var Validate = function() {
    var handleProfile = function() {
        $("#changeProfile").validate({
            errorElement: 'span', //default input error message container
            errorClass: 'help-block', // default input error message class
            rules: {
                name: "required",
                email: {
                    required: true,
                    email: true
                },
                personalId: {
                    required: true,
                    email: true
                },
            },
            messages: {
                name: {
                    required: Lang.get('validation.required', { attribute: Lang.get('template.name') })
                },
                email: {
                    required: Lang.get('validation.required', { attribute: Lang.get('template.email') })
                },
                personalId: {
                    required: Lang.get('validation.required', { attribute: Lang.get('template.email') })
                },
            },
            errorPlacement: function(error, element) { if (element.parent('.input-group').length) { error.insertAfter(element.parent()); } else if (element.hasClass('select2')) { error.insertAfter(element.next('span')); } else { error.insertAfter(element); } },
            invalidHandler: function(event, validator) { //display error alert on form submit 
                var errors = validator.numberOfInvalids();
                if (errors) {
                    $.loader.close(true);
                }
                $('.alert-danger', $('#changeProfile')).show();
            },
            highlight: function(element) { // hightlight error inputs
                $(element).closest('.form-group').addClass('has-error'); // set error class to the control group
            },
            submitHandler: function(form) { // for demo
                $('body').loader(loaderConfig);
                form.submit();
                return false;
            }
        });
        $('#changeProfile input').keypress(function(e) {
            if (e.which == 13) {
                if ($('#changeProfile').validate().form()) {
                    $('#changeProfile').submit(); //form validation success, call ajax form submit
                }
                return false;
            }
        });
    }
    return {
        //main function to initiate the module
        init: function() {
            handleProfile();
        }
    };
}();
jQuery(document).ready(function() {
    Validate.init();
});
$('input[type=text]').change(function() {
    var input = $(this).val();
    var trim_input = input.trim();
    if (trim_input) {
        $(this).val(trim_input);
        return true;
    }
});

/*********** Remove Image code start Here  *************/
$(document).ready(function() {
    if ($("input[name='user_photo']").val() == '') {
        $('.removeimg').hide();
        $('.image_thumb .overflow_layer').css('display', 'none');
    } else {
        $('.removeimg').show();
        $('.image_thumb .overflow_layer').css('display', 'block');
    }

    $(document).on('click', '.removeimg', function(e) {
        $("input[name='user_photo']").val('');
        $("input[name='image_url']").val('');
        $(".fileinput-new div img").attr("src", CDN_PATH + "resources/images/upload_file.gif");

        if ($("input[name='user_photo']").val() == '') {
            $('.removeimg').hide();
            $('.image_thumb .overflow_layer').css('display', 'none');
        } else {
            $('.removeimg').show();
            $('.image_thumb .overflow_layer').css('display', 'block');
        }
    });
});
/************** Remove Images Code end ****************/