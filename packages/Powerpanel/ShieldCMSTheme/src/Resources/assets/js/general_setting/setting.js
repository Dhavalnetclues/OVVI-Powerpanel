var Setting = function() {
    var handleSetting = function() {
        $('#frmSettings').validate({
            errorElement: 'span', //default input error message container
            errorClass: 'help-block', // default input error message class
            focusInvalid: false, // do not focus the last invalid input
            ignore: [],
            rules: {
                site_name: {
                    required: true,
                    noSpace: true
                },
                front_logo_id: "required"
            },
            messages: {
                site_name: Lang.get('validation.required', {
                    attribute: Lang.get('template.settingModule.siteName')
                }),
                front_logo_id: Lang.get('validation.required', {
                    attribute: Lang.get('template.settingModule.frontLogo')
                }),
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
                $('.alert-danger', $('#frmSettings')).show();
            },
            highlight: function(element) { // hightlight error inputs
                $(element).closest('.form-group').addClass('has-error'); // set error class to the control group
            },
            unhighlight: function(element) {
                $(element).closest('.form-group').removeClass('has-error'); // set error class to the control group
            },
            success: function(label) {
                label.closest('.form-group').removeClass('has-error');
                label.remove();
            },
            submitHandler: function(form) {
                $('body').loader(loaderConfig);
                form.submit(); // form validation success, call ajax form submit
            }
        });
        $('#frmSettings input').keypress(function(e) {
            if (e.which == 13) {
                if ($('#frmSettings').validate().form()) {
                    $('#frmSettings').submit(); //form validation success, call ajax form submit
                }
                return false;
            }
        });
    };
    var handleSmtpSetting = function() {
        $('#smtpForm').validate({
            errorElement: 'span', //default input error message container
            errorClass: 'help-block', // default input error message class
            focusInvalid: false, // do not focus the last invalid input
            ignore: [],
            rules: {
                mailer: {
                    required: true
                },
                smtp_server: {
                    required: true,
                    noSpace: true
                },
                smtp_username: {
                    required: true,
                    noSpace: true
                },
                smtp_password: {
                    required: true
                },
                smtp_port: {
                    required: true,
                    noSpace: true
                },
                smtp_sender_name: {
                    required: true,
                    noSpace: true
                },
                smtp_sender_id: {
                    required: true,
                    noSpace: true
                },
                /*mail_content: {
                 required: true
                 }*/
            },
            messages: {
                mailer: Lang.get('validation.required', {
                    attribute: Lang.get('template.settingModule.mailerIsRequired')
                }),
                smtp_server: Lang.get('validation.required', {
                    attribute: Lang.get('template.settingModule.smtp')
                }),
                smtp_username: Lang.get('validation.required', {
                    attribute: Lang.get('template.settingModule.smtpUserName')
                }),
                smtp_password: Lang.get('validation.required', {
                    attribute: Lang.get('template.settingModule.smtpPassword')
                }),
                smtp_port: Lang.get('validation.required', {
                    attribute: Lang.get('template.settingModule.smtpPort')
                }),
                smtp_sender_name: Lang.get('validation.required', {
                    attribute: Lang.get('template.settingModule.smtpSenderName')
                }),
                smtp_sender_id: Lang.get('validation.required', {
                    attribute: Lang.get('template.settingModule.smtpSenderId')
                }),
                /*mail_content: Lang.get('validation.required', {
                 attribute: Lang.get('template.settingModule.mailContent')
                 }),*/
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
                $('.alert-danger', $('#smtpForm')).show();
            },
            highlight: function(element) { // hightlight error inputs
                $(element).closest('.form-group').addClass('has-error'); // set error class to the control group
            },
            unhighlight: function(element) {
                $(element).closest('.form-group').removeClass('has-error'); // set error class to the control group
            },
            success: function(label) {
                label.closest('.form-group').removeClass('has-error');
                label.remove();
            },
            submitHandler: function(form) {
                $('body').loader(loaderConfig);
                form.submit(); // form validation success, call ajax form submit
            }
        });
        $('#smtpForm input').keypress(function(e) {
            if (e.which == 13) {
                if ($('#smtpForm').validate().form()) {
                    $('#smtpForm').submit(); //form validation success, call ajax form submit
                }
                return false;
            }
        });
    };
    var handleCurrencySetting = function() {
        $('#frmCurrency').validate({
            errorElement: 'span', //default input error message container
            errorClass: 'help-block', // default input error message class
            focusInvalid: false, // do not focus the last invalid input
            ignore: [],
            rules: {
                default_currency_symbol: {
                    required: true
                }
            },
            messages: {
                default_currency_symbol: Lang.get('validation.required', {
                    attribute: Lang.get('template.settingModule.defaultCurrencySymbol')
                }),
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
                $('.alert-danger', $('#frmCurrency')).show();
            },
            highlight: function(element) { // hightlight error inputs
                $(element).closest('.form-group').addClass('has-error'); // set error class to the control group
            },
            unhighlight: function(element) {
                $(element).closest('.form-group').removeClass('has-error'); // set error class to the control group
            },
            success: function(label) {
                label.closest('.form-group').removeClass('has-error');
                label.remove();
            },
            submitHandler: function(form) {
                $('body').loader(loaderConfig);
                form.submit(); // form validation success, call ajax form submit
            }
        });
        $('#frmCurrency input').keypress(function(e) {
            if (e.which == 13) {
                if ($('#frmCurrency').validate().form()) {
                    $('#frmCurrency').submit(); //form validation success, call ajax form submit
                }
                return false;
            }
        });
    };
    var handleSeoSetting = function() {
        $('#frmSeo').validate({
            errorElement: 'span', //default input error message container
            errorClass: 'help-block', // default input error message class
            focusInvalid: false, // do not focus the last invalid input
            ignore: [],
            rules: {
                meta_title: {
                    required: true,
                    noSpace: true
                },
                meta_description: {
                    required: true
                },
                xml_file: {
                    accept: "text/xml",
                }

            },
            messages: {
                meta_title: Lang.get('validation.required', {
                    attribute: Lang.get('template.metatitle')
                }),
                meta_description: Lang.get('validation.required', {
                    attribute: Lang.get('template.metadescription')
                }),
                xml_file: {
                    accept: "Only *.xml file format are supported.",
                }
            },
            errorPlacement: function(error, element) {
                if (element.parent('.input-group').length) {
                    error.insertAfter(element.parent());
                } else if (element.hasClass('select2')) {
                    error.insertAfter(element.next('span'));
                } else if (element.attr('name') == 'xml_file') {
                    error.insertAfter($("#xml_file_error"));
                } else {
                    error.insertAfter(element);
                }
            },
            invalidHandler: function(event, validator) { //display error alert on form submit
                var errors = validator.numberOfInvalids();
                if (errors) {
                    $.loader.close(true);
                }
                $('.alert-danger', $('#frmSeo')).show();
            },
            highlight: function(element) { // hightlight error inputs
                $(element).closest('.form-group').addClass('has-error'); // set error class to the control group
            },
            unhighlight: function(element) {
                $(element).closest('.form-group').removeClass('has-error'); // set error class to the control group
            },
            success: function(label) {
                label.closest('.form-group').removeClass('has-error');
                label.remove();
            },
            submitHandler: function(form) {
                $('body').loader(loaderConfig);
                form.submit(); // form validation success, call ajax form submit
            }
        });
        $('#frmSeo input').keypress(function(e) {
            if (e.which == 13) {
                if ($('#frmSeo').validate().form()) {
                    $('#frmSeo').submit(); //form validation success, call ajax form submit
                }
                return false;
            }
        });
    };
    var handleSocialSetting = function() {
        $('#frmSocial').validate({
            errorElement: 'span', //default input error message container
            errorClass: 'help-block', // default input error message class
            focusInvalid: false, // do not focus the last invalid input
            ignore: [],
            rules: {
                fb_link: {
                    url: true,
                },
                twitter_link: {
                    url: true,
                },
                youtube_link: {
                    url: true,
                },
                instagram_link: {
                    url: true,
                },
                linkedin_link: {
                    url: true,
                },
                trip_advisor_link: {
                    url: true,
                }
            },
            messages: {
                fb_link: {
                    url: 'Please enter a valid Facebook link.'
                },
                twitter_link: {
                    url: 'Please enter a valid Twitter link.'
                },
                youtube_link: {
                    url: 'Please enter a valid Youtube link.'
                },
                instagram_link: {
                    url: 'Please enter a valid Instagram link.'
                },
                linkedin_link: {
                    url: 'Please enter a valid LinkedIn link.'
                },
                trip_advisor_link: {
                    url: 'Please enter a valid Trip Advisor link.'
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
                $('.alert-danger', $('#frmSocial')).show();
            },
            highlight: function(element) { // hightlight error inputs
                $(element).closest('.form-group').addClass('has-error'); // set error class to the control group
            },
            unhighlight: function(element) {
                $(element).closest('.form-group').removeClass('has-error'); // set error class to the control group
            },
            success: function(label) {
                label.closest('.form-group').removeClass('has-error');
                label.remove();
            },
            submitHandler: function(form) {
                $('body').loader(loaderConfig);
                form.submit(); // form validation success, call ajax form submit
            }
        });
        $('#frmSocial input').keypress(function(e) {
            if (e.which == 13) {
                if ($('#frmSocial').validate().form()) {
                    $('#frmSocial').submit(); //form validation success, call ajax form submit
                }
                return false;
            }
        });
    };
    var handleSocialShareSetting = function() {
        $('#frmSocialShare').validate({
            errorElement: 'span', //default input error message container
            errorClass: 'help-block', // default input error message class
            focusInvalid: false, // do not focus the last invalid input
            ignore: [],
            rules: {
                fb_id: {
                    required: true
                },
                fb_api: {
                    required: true,
                    noSpace: true
                },
                fb_secret_key: {
                    required: true,
                    noSpace: true
                },
                fb_access_token: {
                    required: true,
                    noSpace: true
                },
                twitter_api: {
                    required: true,
                    noSpace: true
                },
                twitter_secret_key: {
                    required: true,
                    noSpace: true
                },
                twitter_access_token: {
                    required: true,
                    noSpace: true
                },
                twitter_access_token_key: {
                    required: true,
                    noSpace: true
                },
                linkedin_api: {
                    required: true,
                    noSpace: true
                },
                linkedin_secret_key: {
                    required: true,
                    noSpace: true
                },
                linkedin_access_token: {
                    required: true,
                    noSpace: true
                },
                linkedin_access_token_key: {
                    required: true,
                    noSpace: true
                },
            },
            messages: {
                fb_id: Lang.get('validation.required', {
                    attribute: Lang.get('template.settingModule.facebookId')
                }),
                fb_api: Lang.get('validation.required', {
                    attribute: Lang.get('template.settingModule.facebookApi')
                }),
                fb_secret_key: Lang.get('validation.required', {
                    attribute: Lang.get('template.settingModule.facebookSecretKey')
                }),
                fb_access_token: Lang.get('validation.required', {
                    attribute: Lang.get('template.settingModule.facebookAccessToken')
                }),
                twitter_api: Lang.get('validation.required', {
                    attribute: Lang.get('template.settingModule.twitterApi')
                }),
                twitter_secret_key: Lang.get('validation.required', {
                    attribute: Lang.get('template.settingModule.twitterSecretKey')
                }),
                twitter_access_token: Lang.get('validation.required', {
                    attribute: Lang.get('template.settingModule.twitterAccessToken')
                }),
                twitter_access_token_key: Lang.get('validation.required', {
                    attribute: Lang.get('template.settingModule.twitterAccessTokenKey')
                }),
                linkedin_api: Lang.get('validation.required', {
                    attribute: Lang.get('template.settingModule.linkedinApi')
                }),
                linkedin_secret_key: Lang.get('validation.required', {
                    attribute: Lang.get('template.settingModule.linkedinSecretKey')
                }),
                linkedin_access_token: Lang.get('validation.required', {
                    attribute: Lang.get('template.settingModule.linkedinAccessToken')
                }),
                linkedin_access_token_key: Lang.get('validation.required', {
                    attribute: Lang.get('template.settingModule.linkedinAccessTokenKey')
                }),
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
                $('.alert-danger', $('#frmSocialShare')).show();
            },
            highlight: function(element) { // hightlight error inputs
                $(element).closest('.form-group').addClass('has-error'); // set error class to the control group
            },
            unhighlight: function(element) {
                $(element).closest('.form-group').removeClass('has-error'); // set error class to the control group
            },
            success: function(label) {
                label.closest('.form-group').removeClass('has-error');
                label.remove();
            },
            submitHandler: function(form) {
                $('body').loader(loaderConfig);
                form.submit(); // form validation success, call ajax form submit
            }
        });
        $('#frmSocialShare input').keypress(function(e) {
            if (e.which == 13) {
                if ($('#frmSocialShare').validate().form()) {
                    $('#frmSocialShare').submit(); //form validation success, call ajax form submit
                }
                return false;
            }
        });
    };
    var handleOtherSetting = function() {
        $('#otherSettings').validate({
            errorElement: 'span', //default input error message container
            errorClass: 'help-block', // default input error message class
            focusInvalid: false, // do not focus the last invalid input
            ignore: [],
            rules: {
                google_map_key: {
                    required: true,
                    noSpace: true
                },
                google_capcha_key: {
                    required: true,
                    noSpace: true
                },
                php_ini_content: {
                    xssValidation: true,
                }
            },
            messages: {
                google_map_key: Lang.get('validation.required', {
                    attribute: Lang.get('template.settingModule.googleMapKey')
                }),
                google_capcha_key: Lang.get('validation.required', {
                    attribute: Lang.get('template.settingModule.googleCaptchaKey')
                }),
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
                $('.alert-danger', $('#otherSettings')).show();
            },
            highlight: function(element) { // hightlight error inputs
                $(element).closest('.form-group').addClass('has-error'); // set error class to the control group
            },
            unhighlight: function(element) {
                $(element).closest('.form-group').removeClass('has-error'); // set error class to the control group
            },
            success: function(label) {
                label.closest('.form-group').removeClass('has-error');
                label.remove();
            },
            submitHandler: function(form) {
                $('body').loader(loaderConfig);
                form.submit(); // form validation success, call ajax form submit
            }
        });
        $('#otherSettings input').keypress(function(e) {
            if (e.which == 13) {
                if ($('#otherSettings').validate().form()) {
                    $('#otherSettings').submit(); //form validation success, call ajax form submit
                }
                return false;
            }
        });
    };


    var handleSecuritySetting = function() {
        $('#securitySettings').validate({
            errorElement: 'span', //default input error message container
            errorClass: 'help-block', // default input error message class
            focusInvalid: false, // do not focus the last invalid input
            ignore: [],
            rules: {
                max_login_attempts: {
                    required: true,
                    noSpace: true,
                    MinNumber: true,
                    xssValidation: true,
                },
                retry_time_period: {
                    required: true,
                    noSpace: true,
                    xssValidation: true,
                },
                lockout_time: {
                    required: true,
                    noSpace: true,
                    xssValidation: true,
                }
            },
            messages: {
                max_login_attempts: {
                    required: Lang.get('validation.required', { attribute: Lang.get('Max Login Attempts') }),
                    MinNumber: 'Minimum number are allow 5.'
                },
                retry_time_period: Lang.get('validation.required', {
                    attribute: Lang.get('Retry Time Period')
                }),
                lockout_time: Lang.get('validation.required', {
                    attribute: Lang.get('Lock Out Time')
                }),
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
                $('.alert-danger', $('#securitySettings')).show();
            },
            highlight: function(element) { // hightlight error inputs
                $(element).closest('.form-group').addClass('has-error'); // set error class to the control group
            },
            unhighlight: function(element) {
                $(element).closest('.form-group').removeClass('has-error'); // set error class to the control group
            },
            success: function(label) {
                label.closest('.form-group').removeClass('has-error');
                label.remove();
            },
            submitHandler: function(form) {
                $('body').loader(loaderConfig);
                form.submit(); // form validation success, call ajax form submit
            }
        });
        $('#securitySettings input').keypress(function(e) {
            if (e.which == 13) {
                if ($('#securitySettings').validate().form()) {
                    $('#securitySettings').submit(); //form validation success, call ajax form submit
                }
                return false;
            }
        });
    };
    var handleCronSetting = function() {
        $('#cronSettings').validate({
            errorElement: 'span', //default input error message container
            errorClass: 'help-block', // default input error message class
            focusInvalid: false, // do not focus the last invalid input
            ignore: [],
            rules: {
                log_remove_time: {
                    required: true,
                    noSpace: true,
                    xssValidation: true,
                },
            },
            messages: {
                log_remove_time: Lang.get('validation.required', {
                    attribute: Lang.get('template.settingModule.logremovetime')
                }),
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
                $('.alert-danger', $('#cronSettings')).show();
            },
            highlight: function(element) { // hightlight error inputs
                $(element).closest('.form-group').addClass('has-error'); // set error class to the control group
            },
            unhighlight: function(element) {
                $(element).closest('.form-group').removeClass('has-error'); // set error class to the control group
            },
            success: function(label) {
                label.closest('.form-group').removeClass('has-error');
                label.remove();
            },
            submitHandler: function(form) {
                $('body').loader(loaderConfig);
                form.submit(); // form validation success, call ajax form submit
            }
        });
        $('#cronSettings input').keypress(function(e) {
            if (e.which == 13) {
                if ($('#cronSettings').validate().form()) {
                    $('#cronSettings').submit(); //form validation success, call ajax form submit
                }
                return false;
            }
        });
    };


    var handleMagicSetting = function() {
        $('#MagicSettings').validate({
            errorElement: 'span', //default input error message container
            errorClass: 'help-block', // default input error message class
            focusInvalid: false, // do not focus the last invalid input
            ignore: [],
            rules: {
                Magic_Send_Email: {
                    required: true,
                    email: true,
                    noSpace: true,
                    xssValidation: true,
                },
                Magic_Auth_Password: {
                    required: true,
                    noSpace: true,
                    xssValidation: true,
                },
                Magic_Receive_Email: {
                    required: true,
                    email: true,
                    noSpace: true,
                    xssValidation: true,
                },
                Magic_Receive_Password: {
                    required: true,
                    noSpace: true,
                    xssValidation: true,
                },
            },
            messages: {
                Magic_Send_Email: "Magic upload allowed email address is required.",
                Magic_Auth_Password: "Magic upload authentiaon password is required.",
                Magic_Receive_Email: "Magic upload email address is required.",
                Magic_Receive_Password: "Magic upload email password is required.",
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
                $('.alert-danger', $('#cronSettings')).show();
            },
            highlight: function(element) { // hightlight error inputs
                $(element).closest('.form-group').addClass('has-error'); // set error class to the control group
            },
            unhighlight: function(element) {
                $(element).closest('.form-group').removeClass('has-error'); // set error class to the control group
            },
            success: function(label) {
                label.closest('.form-group').removeClass('has-error');
                label.remove();
            },
            submitHandler: function(form) {
                $('body').loader(loaderConfig);
                form.submit(); // form validation success, call ajax form submit
            }
        });
        $('#MagicSettings input').keypress(function(e) {
            if (e.which == 13) {
                if ($('#MagicSettings').validate().form()) {
                    $('#MagicSettings').submit(); //form validation success, call ajax form submit
                }
                return false;
            }
        });
    };
    var handleMaintenancenewSettings = function() {
        $('#MaintenancenewSettings').validate({
            errorElement: 'span', //default input error message container
            errorClass: 'help-block', // default input error message class
            focusInvalid: false, // do not focus the last invalid input
            ignore: [],
            rules: {
                paymenttype: {
                    required: true
                },
                Maintenancenew_Rep_Send_Email: {
                    required: true,
                    noSpace: true,
                    xssValidation: true,
                },
                Maintenancenew_Hour: {
                    time: true
                },
            },
            messages: {
                paymenttype: "Please select the payment type.",
                Maintenancenew_Rep_Send_Email: "Please enter the reporting email address.",

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
                $('.alert-danger', $('#cronSettings')).show();
            },
            highlight: function(element) { // hightlight error inputs
                $(element).closest('.form-group').addClass('has-error'); // set error class to the control group
            },
            unhighlight: function(element) {
                $(element).closest('.form-group').removeClass('has-error'); // set error class to the control group
            },
            success: function(label) {
                label.closest('.form-group').removeClass('has-error');
                label.remove();
            },
            submitHandler: function(form) {
                $('body').loader(loaderConfig);
                form.submit(); // form validation success, call ajax form submit
            }
        });
        $('#MaintenancenewSettings input').keypress(function(e) {
            if (e.which == 13) {
                if ($('#MaintenancenewSettings').validate().form()) {
                    $('#MaintenancenewSettings').submit(); //form validation success, call ajax form submit
                }
                return false;
            }
        });
        jQuery.validator.addMethod("time", function(value, element) {
            if (/^(([0-9])|([0-9][0-9])):([0-9]?[0-9])(:([0-9]?[0-9]))?$/i.test(value)) {
                return true;
            } else {
                return true;
            }
        }, "Please enter a valid hours. <span style='color:blue'>Format: '00:00'</span>");

    };


    var handleMaintenance = function() {
        $('#frmMaintenance').validate({
            errorElement: 'span', //default input error message container
            errorClass: 'help-block', // default input error message class
            focusInvalid: false, // do not focus the last invalid input
            ignore: [],
            rules: {
                'reset[]': "required"
            },
            messages: {
                "reset[]": {
                    required: Lang.get('validation.required', {
                        attribute: Lang.get('template.settingModule.resetOption')
                    }),
                }
            },
            errorPlacement: function(error, element) {
                if (element.attr("type") == "checkbox") {
                    error.insertAfter(element.parents('.checkbox-list-validation'));
                } else {
                    error.insertAfter(element); // for other inputs, just perform default behavior
                }
            },
            invalidHandler: function(event, validator) { //display error alert on form submit
                var errors = validator.numberOfInvalids();
                if (errors) {
                    $.loader.close(true);
                }
                $('.alert-danger', $('#frmMaintenance')).show();
            },
            highlight: function(element) { // hightlight error inputs
                $(element).closest('.form-group').addClass('has-error'); // set error class to the control group
            },
            unhighlight: function(element) {
                $(element).closest('.form-group').removeClass('has-error'); // set error class to the control group
            },
            success: function(label) {
                label.closest('.form-group').removeClass('has-error');
                label.remove();
            },
            submitHandler: function(form) {
                $('body').loader(loaderConfig);
                form.submit(); // form validation success, call ajax form submit
            }
        });
        $('#frmMaintenance input').keypress(function(e) {
            if (e.which == 13) {
                if ($('#frmMaintenance').validate().form()) {
                    $('#frmMaintenance').submit(); //form validation success, call ajax form submit
                }
                return false;
            }
        });
    };
    return {
        //main function to initiate the module
        init: function() {
            handleSetting();
            handleSmtpSetting();
            handleCurrencySetting();
            handleSeoSetting();
            handleSocialSetting();
            handleSocialShareSetting();
            handleOtherSetting();
            handleSecuritySetting();
            handleCronSetting();
            handleMagicSetting();
            handleMaintenancenewSettings();
            handleMaintenance();
        }
    };
}();
// jQuery.validator.addMethod("currencyFormat", function(value, element) {
// 	// allow any non-whitespace characters as the host part
// 	return this.optional( element ) ||  /^(\$|\€|\£)+$/.test(value);
// }, 'Please enter a valid currency symbol.');

var loaderConfig = {
    autoCheck: false,
    size: 16,
    bgColor: 'rgba(0, 0, 0, 0.25)',
    bgOpacity: 0.5,
    fontColor: 'rgba(16, 128, 242, 90)',
    title: 'Loading...'
};

jQuery(document).ready(function() {
    Setting.init();
    if ($('.modulewisesettings').hasClass('active')) {
        getModuleSettingFilterd();
    }

    jQuery.validator.addMethod("noSpace", function(value, element) {
        if (value.trim().length <= 0) {
            return false;
        } else {
            return true;
        }
    }, "This field is required");

    jQuery.validator.addMethod("MinNumber", function(value, element) {
        var attempts = $("#max_login_attempts").val();
        if (attempts < 5) {
            return false;
        } else {
            return true;
        }
    }, 'Minimum number are allow 5.');
    $('#testSMTP').click(function() {
        jQuery.ajax({
            type: "POST",
            url: site_url + '/settings/testMail',
            async: false,
            success: function(data) {
                $('.notify').html(data).show();
                setTimeout(function() {
                    $('.alert-success').hide()
                }, 5000)
            }
        });
    });
    jQuery.validator.addMethod("xssValidation", function(value, element) {
        // allow any non-whitespace characters as the host part
        return this.optional(element) || /<(\w+)((?:\s+\w+(?:\s*=\s*(?:(?:"[^"]*")|(?:'[^']*')|[^>\s]+))?)*)\s*(\/?)>/.test(value) == false ? true : false;
    }, 'Enter valid input');

    $.validator.addMethod('checkmultipleip', function(value, element) {
        var Ip = value.split(",");
        var count = Ip.length;
        var flag = true;
        var i;
        for (i = 0; i < count; i++) {
            if (/^(25[0-5]|2[0-4][0-9]|[01]?[0-9][0-9]?)\.(25[0-5]|2[0-4][0-9]|[01]?[0-9][0-9]?)\.(25[0-5]|2[0-4][0-9]|[01]?[0-9][0-9]?)\.(25[0-5]|2[0-4][0-9]|[01]?[0-9][0-9]?)$/.test(Ip[i])) {
                flag = true;
            } else {
                flag = false;
            }
        }
        return flag;
    }, 'Please enter valid IP address.');

});
$('input[type=text]').change(function() {
    var input = $(this).val();
    var trim_input = input.trim();
    if (trim_input) {
        $(this).val(trim_input);
        return true;
    }
});

var socialcnt = 0;
$(".single_social_link").each(function(index) {
    socialcnt++;
});
$(document).on('click', '.addMoreSocial', function(e) {
    e.preventDefault();
    socialcnt++;
    $('.multi_social_links').append('<div class="single_social_link"><div class="col-md-4"><div class="form-group  form-md-line-input"><input class="form-control" id="available_social_links_for_team' + socialcnt + '_1" autocomplete="off" name="available_social_links_for_team[' + socialcnt + '][title]" type="text" value=""><label class="form_title" for="AVAILABLE_SOCIAL_LINKS_FOR_TEAM_MEMEBER">Title</label></div></div><div class="col-md-4"><div class="form-group  form-md-line-input"><input class="form-control" id="available_social_links_for_team' + socialcnt + '_2" autocomplete="off" name="available_social_links_for_team[' + socialcnt + '][placeholder]" type="text" value=""><label class="form_title" for="AVAILABLE_SOCIAL_LINKS_FOR_TEAM_MEMEBER">Place Holder</label></div></div><div class="col-md-4"><div class="form-group  form-md-line-input"><input class="form-control" id="available_social_links_for_team' + socialcnt + '_3" autocomplete="off" name="available_social_links_for_team[' + socialcnt + '][class]" type="text" value=""><label class="form_title" for="AVAILABLE_SOCIAL_LINKS_FOR_TEAM_MEMEBER">Class</label><a href="javascript:void(0);" class="removeSocial add_more" title="Remove"><i class="ri-delete-bin-line"></i> Remove</a></div></div></div>');

    $('input[name="available_social_links_for_team[' + socialcnt + '][title]"]').rules("add", {
        required: true
    });
});
$(document).on('click', '.removeSocial', function() {
    $(this).parents('.single_social_link').remove();
});


$(document).on('click', '.save-module-settings', function(e) {
    var form = $(this).data('id');
    var Data = $('#' + form).serialize();
    jQuery.ajax({
        type: "POST",
        data: Data,
        url: site_url + '/settings/save-module-settings',
        async: false,
        success: function(data) {
            $('.setting-notify').html(data).show();
            setTimeout(function() {
                $('.alert-success').hide()
            }, 5000);
        }
    });
});




$(document).on('click', '.modulewisesettings', function(e) {
    $('#moduleSearch').val(null);
    getModuleSettingFilterd();
});

$(document).on('click', '.search-module-settings', function(e) {
    getModuleSettingFilterd($('#moduleSearch').val());
});

$(document).on('click', '#modulesettings li', function(e) {
    var moduleId = $(this).data('id');
    getModuleSetting(moduleId);
});

function getModuleSetting(moduleId) {
    jQuery.ajax({
        type: "POST",
        data: {
            'moduleId': moduleId
        },
        url: site_url + '/settings/get-save-module-settings',
        async: false,
        dataType: 'JSON',
        success: function(data) {
            var dom = '#' + data.moduleName + '_' + data.moduleId;
            $.each(data, function(key, data) {
                $(dom + ' input[name=' + key + ']').val(data);
                if (key.indexOf("checkbox_") >= 0) {
                    var chk = data;
                    $.each(chk, function(key, data) {
                        $(dom + ' input[value=' + data + ']').prop('checked', true);
                        $(dom + ' input[value=' + data + ']').bootstrapSwitch('state', true);
                    });
                }
            });
            $('#modulesettings #moduleDiv :checkbox').bootstrapSwitch({ size: 'small' });
        }
    });
}

function getModuleSettingFilterd() {
    jQuery.ajax({
        type: "POST",
        data: {
            'term': term
        },
        url: site_url + '/settings/get-filtered-modules',
        async: false,
        dataType: 'HTML',
        success: function(data) {
            $('#modulesettings #moduleDiv').empty().html(data);
            $('#modulesettings #moduleDiv :checkbox').bootstrapSwitch({ size: 'small' });
            $('.tabbable').loader(loaderConfig);
        },
        complete: function() {
            $.loader.close(true);

            var moduleId = $('#modulesettings li[class=active]').data('id');

            if (moduleId == undefined) {
                moduleId = $('#modulesettings li:first-child').data('id');
            }

            getModuleSetting(moduleId);

            $('.scroller').slimScroll({
                alwaysVisible: true,
                size: '3px'
            });

            var scrollToVal = $('.scroller ul li[class=active]').position().top;
            $('.scroller').slimScroll({ scrollTo: scrollToVal + 'px' });

        }
    });
}

function KeycheckOnlyAmount(e) {
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
    if (r >= 65 && r <= 90 || r >= 97 && r <= 122 || r >= 33 && r <= 41 || r >= 42 && r <= 43 || r >= 45 && r <= 45 || r >= 47 && r <= 47 || r >= 58 && r <= 64 || r >= 91 && r <= 96 || r >= 123 && r <= 126) {
        return false
    }
    return true
}

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