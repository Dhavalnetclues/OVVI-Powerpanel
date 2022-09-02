/**
 * This method validates contacts's form fields
 * since   2016-12-24
 * author  NetQuick
 */
var Validate = function() {
    var handleContact = function() {
        $("#frmContactUS").validate({
            errorElement: 'span', //default input error message container
            errorClass: 'help-block', // default input error message class
            rules: {
                name: {
                    required: true,
                    noSpace: true,
                    xssProtection: true,
                    no_url: true
                },
                email: {
                    required: true,
                    email: true,
                    noSpace: true,
                },
                address: {
                    xssProtection: true,
                    no_url: true
                },
                mailingaddress: {
                    xssProtection: true,
                    no_url: true
                },
                fax: {
                    xssProtection: true,
                    no_url: true
                },
            },
            messages: {
                name: {
                    required: Lang.get('validation.required', {
                        attribute: Lang.get('template.name')
                    })
                },
                address: {
                    required: Lang.get('validation.required', {
                        attribute: Lang.get('template.contactModule.address')
                    })
                },
                mailingaddress: { required: "Mailing address field is required." },
            },
            invalidHandler: function(event, validator) { //display error alert on form submit
                var errors = validator.numberOfInvalids();
                if (errors) {
                    $.loader.close(true);
                }
                $('.alert-danger', $('#frmContactUS')).show();
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
                return false;
            }
        });
        $('#frmContactUS input').keypress(function(e) {
            if (e.which == 13) {
                if ($('#frmContactUS').validate().form()) {
                    $('#frmContactUS').submit(); //form validation success, call ajax form submit
                }
                return false;
            }
        });
    }
    return {
        //main function to initiate the module
        init: function() {
            handleContact();
        }
    };
}();
jQuery(document).ready(function() {
    Validate.init();
    $.validator.addMethod("phonenumber", function(value, element) {
        var newVal = value.replace(/^\D+/g, '');
        if (parseInt(newVal) <= 0 || newVal.match(/\d+/g) == null) {
            return false;
        } else {
            return true;
        }
    }, 'Please enter a valid phone number.');
    jQuery.validator.addMethod("noSpace", function(value, element) {
        if (value.trim().length <= 0) {
            return false;
        } else {
            return true;
        }
    }, "This field is required");
});
jQuery.validator.addMethod("phoneFormat", function(value, element) {
    // allow any non-whitespace characters as the host part
    return this.optional(element) || /((\(\d{3}\) ?)|(\d{3}-))?\d{3}-\d{4}/.test(value);
}, 'Please enter a valid phone number.');

jQuery.validator.addMethod("minStrict", function(value, element) {
    // allow any non-whitespace characters as the host part
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

$(document).ready(function() {
    $('#phone_no').mask("+0 (000) 000 0000", {
        placeholder: "+x (xxx) xxx xxxx"
    });
    $('#fax').mask("+0 (000) 000 0000", {
        placeholder: "+x (xxx) xxx xxxx"
    });
});
//########################extra field add js###########################################


// $(document).on('click', '.addMoreEmail', function(e) {
//     e.preventDefault();
//     if ($('.emailField').length >= 2) {
//         $(this).hide();
//     }

//     if ($('.emailField').length < 3) {
//         emcnt++;
//         $('.multi-email').append('<div class="emailField form-group form-md-line-input"> <input class="form-control input-sm" maxlength="100" id="email' + emcnt + '" placeholder="Email" autocomplete="off" name="email[' + emcnt + ']" type="text"> <label class="form_title" for="email[' + emcnt + ']">Email</label> <a href="javascript:void(0);" class="removeEmail add_more" title="Remove"><i class="ri-delete-bin-line"></i> Remove</a> <span class="help-block"> </span> </div>');
//         $('input[name="email[' + emcnt + ']"]').rules("add", {
//             email: true,
//             noSpace: true
//         });
//     }
// });

// $(document).on('click', '.removeEmail', function() {
//     $(this).parent().remove();
//     $('.addMoreEmail').show();
// });

// $(document).on('click', '.addMorePhone', function(e) {
//     e.preventDefault();

//     if ($('.phoneField').length >= 2) {
//         $(this).hide();
//     }

//     if ($('.phoneField').length < 3) {
//         phcnt++;
//         $('.multi-phone').append('<div class="phoneField form-group form-md-line-input"> <input class="form-control input-sm mask_phone_' + phcnt + '" id="phone_no' + phcnt + '" placeholder="Phone No" autocomplete="off" maxlength="20" onkeypress="javascript: return KeycheckOnlyPhonenumber(event);" name="phone_no[' + phcnt + ']" type="text"> <label class="form_title" for="phone_no">Phone No</label> <a href="javascript:void(0);" class="removePhone add_more" title="Remove"><i class="ri-delete-bin-line"></i> Remove</a> <span class="help-block"> </span> </div>');
//         $('input[name="phone_no[' + phcnt + ']"]').rules("add", {
//             minlength: 5,
//             maxlength: 20,
//             phonenumber: {
//                 depends: function() {
//                     if (($(this).val()) != '') {
//                         return true;
//                     } else {
//                         return false;
//                     }
//                 }
//             }
//         });
//     }
// });

// $(document).on('click', '.removePhone', function() {
//     $(this).parent().remove();
//     $('.addMorePhone').show();
// });


function load_validation() {

    if ($('.phoneField').length >= 1) {
        var phcnt = 0;
        for (var i = 0; i <= phcnt; i++) {
            $('input[name="phone_no[' + i + ']"]').rules("add", {
                minlength: 5,
                maxlength: 20,
                phonenumber: {
                    depends: function() {
                        if (($(this).val()) != '') {
                            return true;
                        } else {
                            return false;
                        }
                    }
                },
                // required: {
                //     depends: function() {
                //         if ($(this).prop('name') == 'phone_no[0]') {
                //             return true;
                //         } else {
                //             return false;
                //         }
                //     }
                // },
                messages: {
                    required: "Phone field is required.",
                }
            });
        }


    }

    if ($('.emailField').length >= 1) {
        var emcnt = 0;
        for (var i = 0; i <= emcnt; i++) {

            $('input[name="email[' + i + ']"]').rules("add", {
                required: true,
                email: true,
                noSpace: true,
                messages: {
                    required: "Email field is required.",
                }
            });
        }
    }

    if ($('.emailField').length >= 2) {
        $('.addMoreEmail').hide();
    }

    if ($('.phoneField').length >= 2) {
        $('.addMorePhone').hide();
    }

}
