/**
 * This method validates team form fields
 * since   2016-12-24
 * author  NetQuick
 */


var Validate = function() {
    var handleTeam = function() {
        $("#frmformsandfees").validate({
            errorElement: 'span', //default input error message container
            errorClass: 'help-block', // default input error message class
            ignore: [],
            rules: {
                title: {
                    required: true,
                    noSpace: true
                },
                sector: {
                    required: true,
                },
                order: {
                    required: true,
                    minStrict: true,
                    number: true,
                    noSpace: true
                },
                varMetaTitle: {
                    required: true,
                    noSpace: true
                },
                varMetaKeyword: {
                    required: true,
                    noSpace: true
                },
                varMetaDescription: {
                    required: true,
                    noSpace: true
                }
            },
            messages: {
                title: {
                    required: "Title field is required.",
                },
                sector: {
                    required: "Sector field is required.",
                },
                order: {
                    required: Lang.get('validation.required', {
                        attribute: Lang.get('template.displayorder')
                    })
                },
                varMetaTitle: Lang.get('validation.required', {
                    attribute: Lang.get('template.metatitle')
                }),
                varMetaKeyword: Lang.get('validation.required', {
                    attribute: Lang.get('template.metakeyword')
                }),
                varMetaDescription: Lang.get('validation.required', {
                    attribute: Lang.get('template.metadescription')
                })
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
                $('.alert-danger', $('#frmformsandfees')).show();
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
        $('#frmformsandfees input').on('keypress', function(e) {
            if (e.which == 13) {
                if ($('#frmformsandfees').validate().form()) {
                    $('#frmformsandfees').submit(); //form validation success, call ajax form submit
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
jQuery(document).ready(function() {
    Validate.init();

    var social_links = $('input[name^="social_link_"]').length;
    if (social_links > 0) {
        for (i = 0; i < social_links; i++) {
            $('input[name="social_link_' + i + '"]').rules("add", {
                url: true,
                messages: {
                    url: "Please enter valid URL"
                }
            });
        }
    }

    jQuery.validator.addMethod("noSpace", function(value, element) {
        if (value.trim().length <= 0) {
            return false;
        } else {
            return true;
        }
    }, "This field is required");
});
$.validator.addMethod("phonenumber", function(value, element) {

    if (value != '') {
        var numberPattern = /\d+/g;
        var newVal = value.replace(/\D/g, 0);
        if (parseInt(newVal) <= 0 || newVal.match(/\d+/g) == null) {
            return false;
        } else {
            return true;
        }
    } else {
        return true;
    }

}, 'Please enter a valid phone number.');
/*jQuery.validator.addMethod("urlFormat", function(url) {
 return /^http(s)?:\/\/(www\.)?[a-z0-9]+([\-\.]{1}[a-z0-9]+)*\.[a-z]{2,5}(:[0-9]{1,5})?(\/.*)?$/.test(url);
 },'Enter valid url format');*/
jQuery.validator.addMethod("emailFormat", function(value, element) {
    // allow any non-whitespace characters as the host part
    return this.optional(element) || /^[_a-z0-9-]+(\.[_a-z0-9-]+)*@[a-z0-9-]+(\.[a-z0-9-]+)*(\.[a-z]{2,4})$/.test(value);
}, 'Enter valid email format');
jQuery.validator.addMethod("minStrict", function(value, element) {
    // allow any non-whitespace characters as the host part
    if (value > 0) {
        return true;
    } else {
        return false;
    }
}, 'Display order must be a number higher than zero');
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
        $(".fileinput-preview").html('<div class="dz-message needsclick w-100 text-center"><div class="dropzone_icon"><i class="display-5 text-muted ri-upload-cloud-2-fill"></i></div><h5 class="sbold dropzone-title">Drop files here or click to upload</h5></div>');
        // $(".fileinput-new div img").attr("src", site_url + "/resources/images/upload_file.gif");
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