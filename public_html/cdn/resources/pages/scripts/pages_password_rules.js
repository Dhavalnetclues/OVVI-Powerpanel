"use strict";
var PasswordRules = function() {
    return {
        minMaxLengthRules: function(inputId = false, value = false) {
            if ((value.length < 6) || (value.length > 20)) {
                $("#" + inputId + '_info').find('.lengthInfo').removeClass('valid').addClass('invalid');
            } else {
                $("#" + inputId + '_info').find('.lengthInfo').removeClass('invalid').addClass('valid');
            }
        },
        numberRules: function(inputId = false, value = false) {
            if (value.match(/\d/)) {
                $("#" + inputId + '_info').find('.numberinfo').removeClass('invalid').addClass('valid');
            } else {
                $("#" + inputId + '_info').find('.numberinfo').removeClass('valid').addClass('invalid');
            }
        },
        specialRules: function(inputId = false, value = false) {
            if (value.match(/[^\w\s]/)) {
                $("#" + inputId + '_info').find('.specialinfo').removeClass('invalid').addClass('valid');
            } else {
                $("#" + inputId + '_info').find('.specialinfo').removeClass('valid').addClass('invalid');
            }
        },
        alphabetRules: function(inputId = false, value = false) {
            if (value.match(/[a-z]/)) {
                $("#" + inputId + '_info').find('.letterinfo').removeClass('invalid').addClass('valid');
            } else {
                $("#" + inputId + '_info').find('.letterinfo').removeClass('valid').addClass('invalid');
            }
            //validate capital letter
            if (value.match(/[A-Z]/)) {
                $("#" + inputId + '_info').find('.capitalletterinfo').removeClass('invalid').addClass('valid');
            } else {
                $("#" + inputId + '_info').find('.capitalletterinfo').removeClass('valid').addClass('invalid');
            }
        },
        init: function() {}
    }
}();
$(document).ready(function() {
    PasswordRules.init();
    $.validator.addMethod("passwordrules", function(value) {
        //return /^[A-Z0-9]*$/.test(value) && /[A-Z]/.test(value) && /\d/.test(value)
       var regex = /^(?=.*?[A-Z])(?=.*?[a-z])(?=.*?[0-9])(?=.*?[^\w\s]).{6,20}$/;
       return regex.test(value);

    });
    $('input[id=newpassword]').keyup(function() {
        var pswd = $(this).val();
        var inputId = $(this).attr('id');
        PasswordRules.minMaxLengthRules(inputId, pswd);
        PasswordRules.alphabetRules(inputId, pswd);
        PasswordRules.numberRules(inputId, pswd);
        PasswordRules.specialRules(inputId, pswd);

        $("#" + inputId + '_info').show();
    }).focus(function() {
        var pswd = $(this).val();
        var inputId = $(this).attr('id');
        $("#" + inputId + '_info').show();
        PasswordRules.minMaxLengthRules(inputId, pswd);
        PasswordRules.alphabetRules(inputId, pswd);
        PasswordRules.numberRules(inputId, pswd);
        PasswordRules.specialRules(inputId, pswd);
    }).blur(function() {
        var pswd = $(this).val();
        var inputId = $(this).attr('id');
        PasswordRules.minMaxLengthRules(inputId, pswd);
        PasswordRules.alphabetRules(inputId, pswd);
        PasswordRules.numberRules(inputId, pswd);
        PasswordRules.specialRules(inputId, pswd);
        $("#" + inputId + '_info').hide();
    });
});