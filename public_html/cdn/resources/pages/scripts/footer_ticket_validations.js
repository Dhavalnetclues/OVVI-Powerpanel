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
        checkVersion: function () {
            var radioValue = $("input[name='bannerversion']:checked").val();
            if (radioValue == 'img_banner') {
                $('.imguploader').show();
                $('.viduploader').addClass('hide');
            } else {
                $('.imguploader').hide();
                $('.viduploader').removeClass('hide');
            }
        },
    }
}();
/**
 * This method validates blog form fields
 * since   2016-12-24
 * author  NetQuick
 */
var FooterValidate = function () {
    var handleBlog = function () {

        $("#Ticket_Form").validate({
            errorElement: 'span', //default input error message container
            errorClass: 'help-block', // default input error message class
            ignore: [],
            rules: {
                Name: {
                    required: true,
                    noSpace: true,
                    xssProtection: true,
                    no_url:true,
                    check_special_char:true,
                    badwordcheck:true,
                    languageTest:true,
                },
                Link: {
                    required: true,
                    noSpace: true,
                    xssProtection: true,
                    check_special_char:true,
                    badwordcheck:true,
                },
                varType: {
                    required: true,
                    noSpace: true,
                    xssProtection: true,
                    check_special_char:true,
                    badwordcheck:true,
                },
                "file-1[]": {
                    extension: "jpg,jpeg,png"
                },
                varMessage: {
                    required: true,
                    noSpace: true,
                    xssProtection: true,
                    check_special_char:true,
                    badwordcheck:true,
                    languageTest:true,
                    no_url:true,
                }
            },
            messages: {
                Name: {
                	required:"Please enter name.",
                },
                Link: {
                	required:"Please enter link.",
                },
                varType: {
                	required:"Please select type.",
                },
                "file-1[]": {
                    extension: "Only *.jpg, *.jpeg, *.png  file are supported."
                },
                varMessage: {
                	required:"Please enter message.",
                }
            },
            errorPlacement: function (error, element) {
                if (element.parent('.input-group').length) {
                    error.insertAfter(element.parent());
                } else if (element.hasClass('select2')) {
                    error.insertAfter(element.next('span'));
                } else if (element.hasClass('fkimg_val')) {
                    error.insertAfter($("#fkimg_val123"));
                } else {
                    error.insertAfter(element);
                }
            },
            invalidHandler: function (event, validator) { //display error alert on form submit   
                $('.alert-danger', $('#Ticket_Form')).show();
                var errors = validator.numberOfInvalids();
                if (errors) {
                    $.loader.close(true);
                }

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
                return false;
            }
        });
        $('#Ticket_Form input').keypress(function (e) {
            if (e.which == 13) {
                if ($('#Ticket_Form').validate().form()) {
                    $('#Ticket_Form').submit(); //form validation success, call ajax form submit
                }
                return false;
            }
        });
    }
    return {
        //main function to initiate the module
        init: function () {
            handleBlog();
        }
    };
}();


var AuthValidate = function () {
    var handleContact11 = function () {
        $(".random_form").validate({
            errorElement: 'span', //default input error message container
            errorClass: 'help-block', // default input error message class
            ignore: [],
            rules: {
                random_code: {
                    required: true
                }
            },
            messages: {
                random_code: {
                    required: "Please enter two-factor authentication code."
                }

            },
            invalidHandler: function (event, validator) { //display error alert on form submit   
                $('.alert-danger', $('.random_form')).show();
            },
            highlight: function (element) { // hightlight error inputs
                $(element).closest('.form-group').addClass('has-error'); // set error class to the control group
            },
            unhighlight: function (element) {
                $(element).closest('.form-group').removeClass('has-error'); // set error class to the control group
            },
            submitHandler: function (form) {
                $("#verify").attr("disabled", true);
                var frmData = $(form).serialize();
                jQuery.ajax({
                    type: "POST",
                    url: site_url + '/powerpanel/checkrandom',
                    data: frmData + "&url=" + window.location,
                    dataType: "json",
                    success: function (data) {
                        if (data.success == 1) {
                            document.getElementById("tncpopup").style.display = "none";
//                            location.reload(true);
                            var newUrl = site_url + '/powerpanel/dashboard';
                            window.location.href = newUrl;
                        } else {
                            alert("Please enter correct access code as you received in your personal email id.");
                            location.reload(true);
                        }

                    }
                });
                return false;
            }
        });
    }
    return {
        //main function to initiate the module
        init: function () {
            handleContact11();
        }
    };
}();
var SecurityQuestionsValidate = function () {
    var handleSecurityQuestions = function () {
        $(".security_questions_form").validate({
            errorElement: 'span', //default input error message container
            errorClass: 'help-block', // default input error message class
            ignore: [],
            rules: {
                SecurityAnswer: {
                    required: true
                }
            },
            messages: {
                SecurityAnswer: {
                    required: "Please enter the answer."
                }

            },
            invalidHandler: function (event, validator) { //display error alert on form submit   
                $('.alert-danger', $('.security_questions_form')).show();
            },
            highlight: function (element) { // hightlight error inputs
                $(element).closest('.form-group').addClass('has-error'); // set error class to the control group
            },
            unhighlight: function (element) {
                $(element).closest('.form-group').removeClass('has-error'); // set error class to the control group
            },
            submitHandler: function (form) {
                $("#verify").attr("disabled", true);
                var frmData = $(form).serialize();
                jQuery.ajax({
                    type: "POST",
                    url: site_url + '/powerpanel/checkanswer',
                    data: frmData,
                    dataType: "json",
                    success: function (data) {
                        $("#verify").removeAttr("disabled");
                        if (data.success == 1) {
                            document.getElementById("securitypopup").style.display = "none";
                            location.reload(true);
                        } else {
                            $("#securitypopup").find("#not_match").show();
                            $("#securitypopup").find("#not_match").text("Please enter the correct answer.");
                            setTimeout(function () {
                                $("#securitypopup").find("#not_match").hide();
                            }, 3000);
                        }

                    }
                });
                return false;
            }
        });
    }
    return {
        //main function to initiate the module
        init: function () {
            handleSecurityQuestions();
        }
    };
}();


jQuery(document).ready(function () {
    FooterValidate.init();
    AuthValidate.init();
    SecurityQuestionsValidate.init();
});