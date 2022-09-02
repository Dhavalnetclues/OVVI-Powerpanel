/**
 * This method validates team form fields
 * since   2016-12-24
 * author  NetQuick
 */
var Validate = function () {
    var handleTeam = function () {
        $("#frmfmbroadcasting").validate({
            errorElement: 'span', //default input error message container
            errorClass: 'help-block', // default input error message class
            ignore: [],
            rules: {
                title: {
                    required: true,
                    noSpace: true
                },
                frequency: {
                    required: true
                },
                img_id: {
                    required: true
                },
                link: {
                    required: true,
                    url:true
                },
                order: {
                    required: true,
                    minStrict: true,
                    number: true,
                    noSpace: true
                },
            },
            messages: {
                title: 'Station Name field is required.',
                img_id: {
                    required: 'Logo field is required.'
                },
                frequency: {
                    required: "Frequency field is required."
                },
                link: {
                    required: "Listen Live Link field is required",
                    url:"Invalid Url."
                },
                order: {
                    required: Lang.get('validation.required', {
                        attribute: Lang.get('template.displayorder')
                    })
                }
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
                $('.alert-danger', $('#frmfmbroadcasting')).show();
            },
            highlight: function (element) { // hightlight error inputs
                $(element).closest('.form-group').addClass('has-error'); // set error class to the control group
            },
            unhighlight: function (element) {
                $(element).closest('.form-group').removeClass('has-error'); // set error class to the control group
            },
            submitHandler: function (form) {
                $('body').loader(loaderConfig);
                form.submit();
                $("button[type='submit']").attr('disabled', 'disabled');
                return false;
            }
        });
        $('#frmfmbroadcasting input').on('keypress', function (e) {
            if (e.which == 13) {
                if ($('#frmfmbroadcasting').validate().form()) {
                    $('#frmfmbroadcasting').submit(); //form validation success, call ajax form submit
                    $("button[type='submit']").attr('disabled', 'disabled');
                }
                return false;
            }
        });
    }
    return {
        //main function to initiate the module
        init: function () {
            handleTeam();
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
    }, "Space is not allowed.");

    jQuery.validator.addMethod("minStrict", function (value, element) {
        // allow any non-whitespace characters as the host part
        if (value > 0) {
            return true;
        } else {
            return false;
        }
    }, 'Display order must be a number higher than zero');


});

$('input[type=text]').on('change', function () {
    var input = $(this).val();
    var trim_input = input.trim();
    if (trim_input) {
        $(this).val(trim_input);
        return true;
    }
});
/*********** Remove Image code start Here  *************/
$(document).ready(function ()
{
    if ($("input[name='img_id']").val() == '') {
        $('.removeimg').hide();
        $('.image_thumb .overflow_layer').css('display', 'none');
    } else {
        $('.removeimg').show();
        $('.image_thumb .overflow_layer').css('display', 'block');
    }
    $(document).on('click', '.removeimg', function (e) {
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
 function isNumberKey(evt)
       {
          var charCode = (evt.which) ? evt.which : evt.keyCode;
          if (charCode != 46 && charCode > 31 
            && (charCode < 48 || charCode > 57))
             return false;

          return true;
       }
/************** Remove Images Code end ****************/