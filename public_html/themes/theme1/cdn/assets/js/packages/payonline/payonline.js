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

$("#next1").on('click', function(event) {

    $("#personalInfo_form").validate({
        errorElement: 'span', //default input error message container
        errorClass: 'help-block error', // default input error message class
        ignore: [],
        rules: {
            fullname: {
                required: true,
            },
            companyName: {
                required: true,
            },
            email: {
                required: true,
                emailFormat: true,
            },
            phoneno: {
                minlength: 6,
                maxlength: 20,
                phonenumber: {
                    required: false,
                }
            },
        },
        messages: {
            fullname: {
                required: "Name is required.",
            },
            companyName: {
                required: "Company name is required.",
            },
            email: {
                required: "Email is required.",
            }
        },
        errorPlacement: function(error, element) {
            error.insertAfter(element);
        },
        invalidHandler: function(event, validator) { //display error alert on form submit   
            console.log('in invalidHandler');
            $('.alert-danger', $('#personalInfo_form')).show();
        },
        highlight: function(element) { // hightlight error inputs
            $(element).closest('.form-group').addClass('has-error'); // set error class to the control group
        },
        unhighlight: function(element) {
            $(element).closest('.form-group').removeClass('has-error'); // set error class to the control group
        },
        submitHandler: function(form) {
            $("#collapseTwo").collapse("show");
            $("#collapseOne").collapse("hide");

            $("#personalInfo_name").val($("#fullname").val());
            $("#personalInfo_companyname").val($("#companyName").val());
            $("#personalInfo_email").val($("#email").val());
            $("#personalInfo_phone").val($("#phone").val());


            $("#info1").html($("#fullname").val() + ' !');
            $('#edit1, #info1').removeClass("d-none");
            event.preventDefault();

            return false;
        }
    });
    $('#personalInfo_form input').keypress(function(e) {
        if (e.which == 13) {
            console.log($('#personalInfo_form').validate().form());
            if ($('#personalInfo_form').validate().form()) {
                $("#personalInfo_form").attr("disabled", "disabled");
                SetBackGround();
                // $('#edit1, #info1').removeClass("d-none");
                // $('#collapseOne').submit(); //form validation success, call ajax form submit
            }
            return false;
        }
    });

})

$("#amount").on('change', function(event) {
    let amount = parseFloat($("#amount").val()).toFixed(2)
    if (isNaN(amount)) {
        $("#payableamount").html('$' + 0);
        $("#processamount").html('$' + 0);
        $("#totalAmount").html('$' + 0);
    } else {
        let tax = parseFloat(amount * 0.04).toFixed(2)
        $("#payableamount").html('$' + amount);
        $("#processamount").html('$' + tax);
        $("#totalAmount").html('$' + (parseFloat(amount) + parseFloat(tax)));
    }

})

$('input[name="currency"]').on('change', function(event) {
    let currencyType = $('input[name="currency"]:checked').val()
    $("#info4").html('This payment is in the ' + currencyType)
})

$("#next2").on('click', function(event) {

    $("#paymentInfo_form").validate({
        errorElement: 'span', //default input error message container
        errorClass: 'help-block error', // default input error message class
        ignore: [],
        rules: {
            invoiceNumber: {
                required: true,
            },
            amount: {
                required: true,
            },
            currency: {
                required: true,
            },
            cardType: {
                required: true,
            },
        },
        messages: {
            invoiceNumber: {
                required: "Invoice number is required.",
            },
            amount: {
                required: "Amount is required.",
            },
            currency: {
                required: "Currency is required.",
            },
            cardType: {
                required: "Please select card type.",
            }
        },
        errorPlacement: function(error, element) {
            if (element.attr("name") == "cardType") {
                error.insertAfter(element.parent());
            } else {
                error.insertAfter(element);
            }
        },
        invalidHandler: function(event, validator) { //display error alert on form submit   
            $('.alert-danger', $('#paymentInfo_form')).show();
        },
        highlight: function(element) { // hightlight error inputs
            $(element).closest('.form-group').addClass('has-error'); // set error class to the control group
        },
        unhighlight: function(element) {
            $(element).closest('.form-group').removeClass('has-error'); // set error class to the control group
        },
        submitHandler: function(form) {
            let amount = parseFloat($("#amount").val()).toFixed(2)
            let tax = parseFloat(amount * 0.04).toFixed(2)
            $("#payableamount").html('$' + amount);
            $("#processamount").html('$' + tax);
            $("#totalAmount").html('$' + (parseFloat(amount) + parseFloat(tax)));
            $("#info2").html($('input[name="currency"]:checked').val() + ' $' + (parseFloat(amount) + parseFloat(tax)) + ' for Invoice Number ' + $("#invoiceNumber").val());

            $("#paymentInfo_invoice").val($("#invoiceNumber").val());
            $("#paymentInfo_amount").val((parseFloat(amount) + parseFloat(tax)));
            $("#paymentInfo_currency").val($('input[name="currency"]:checked').val());
            $("#paymentInfo_cardType").val($("#cardType").val());

            $("#collapseThree").collapse("show");
            $("#collapseTwo").collapse("hide");
            $('#edit2, #info2, #info3').removeClass("d-none");
            event.preventDefault();

            return false;
        }
    });
    $('#paymentInfo_form input').keypress(function(e) {
        if (e.which == 13) {
            console.log($('#paymentInfo_form').validate().form());
            if ($('#paymentInfo_form').validate().form()) {
                $("#paymentInfo_form").attr("disabled", "disabled");
                SetBackGround();
                // $('#edit2, #info2, #info3').removeClass("d-none");
                // $('#collapseOne').submit(); //form validation success, call ajax form submit
            }
            return false;
        }
    });

})

$("#pay").on('click', function(event) {
    $("#cardInfo_form").submit();
})

var Validate = function() {
    var handleContact = function() {

        $("#cardInfo_form").validate({
            errorElement: 'span', //default input error message container
            errorClass: 'help-block error', // default input error message class
            ignore: [],
            rules: {
                nameOnCard: {
                    required: true,
                },
                cardnumber: {
                    required: true,
                },
                month: {
                    required: true,
                },
                year: {
                    required: true,
                },
                cvv: {
                    required: true,
                }
            },
            messages: {
                nameOnCard: {
                    required: "Enter your name on card.",
                },
                cardnumber: {
                    required: "Enter your card number.",
                },
                month: {
                    maxlength: "Please select a expiry month.",
                },
                year: {
                    maxlength: "Please select a expiry year.",
                },
                cvv: {
                    required: "Enter your cvv.",
                }
            },
            errorPlacement: function(error, element) {
                if (element.attr("name") == "month" || element.attr("name") == "year") {
                    error.insertAfter(element.parent());
                } else {
                    error.insertAfter(element);
                }
            },
            invalidHandler: function(event, validator) { //display error alert on form submit   
                $('.alert-danger', $('#cardInfo_form')).show();
            },
            highlight: function(element) { // hightlight error inputs
                $(element).closest('.form-group').addClass('has-error'); // set error class to the control group
            },
            unhighlight: function(element) {
                $(element).closest('.form-group').removeClass('has-error'); // set error class to the control group
            },
            submitHandler: function(form) {
                $("#pay").attr("disabled", "disabled");
                form.submit();
                return false;
            }
        });
        $('#cardInfo_form input').keypress(function(e) {
            if (e.which == 13) {
                if ($('#cardInfo_form').validate().form()) {
                    $("#pay").attr("disabled", "disabled");
                    $('#cardInfo_form').submit(); //form validation success, call ajax form submit
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
        var numberPattern = /\d+/g;
        var newVal = value.replace(/\D/g);
        if (parseInt(newVal) <= 0) {
            return false;
        } else {
            return true;
        }
    }, 'Please enter a valid phone number.');

    var blacklist = /\b(nude|naked|sex|porn|porno|sperm|fuck|penis|pussy|vagina|boobs|asshole|shit|bitch|motherfucker|dick|orgasm|fucker)\b/; /* many more banned words... */
    $.validator.addMethod("badwordcheck", function(value) {
        return !blacklist.test(value.toLowerCase());
    }, "Please remove bad word/inappropriate language.");
    $.validator.addMethod("minimum_length", function(value, element) {
        if ($("#phone_no").val().length < 5 || $("#phone_no").val().length > 20) {
            return false;
        } else {
            return false;
        }
    }, 'Please enter a phone number minimum 5 digits and maximum 20 digits.');
    jQuery.validator.addMethod("noSpace", function(value, element) {
        if (value.trim().length <= 0) {
            return false;
        } else {
            return true;
        }
    }, "No space please and don't leave it empty");
    jQuery.validator.addMethod("emailFormat", function(value, element) {
        // allow any non-whitespace characters as the host part
        return this.optional(element) || /^[_A-Za-z0-9-]+(\.[_A-Za-z0-9-]+)*@[A-Za-z0-9-]+(\.[A-Za-z0-9-]+)*(\.[A-Za-z]{2,4})$/.test(value);
    }, 'Enter valid email format');
    jQuery.validator.addMethod("messageValidation", function(value, element) {
        // allow any non-whitespace characters as the host part
        return this.optional(element) || /<(\w+)((?:\s+\w+(?:\s*=\s*(?:(?:"[^"]*")|(?:'[^']*')|[^>\s]+))?)*)\s*(\/?)>/.test(value) == false ? true : false;
    }, 'Enter valid message');
    jQuery.validator.addMethod("xssProtection", function(value, element) {
        // allow any non-whitespace characters as the host part
        return this.optional(element) || /<(\w+)((?:\s+\w+(?:\s*=\s*(?:(?:"[^"]*")|(?:'[^']*')|[^>\s]+))?)*)\s*(\/?)>/.test(value) == false ? true : false;
    }, 'Enter valid input');
    $.validator.addMethod("check_special_char", function(value, element) {
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
    $.validator.addMethod('no_url', function(value, element) {
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
});