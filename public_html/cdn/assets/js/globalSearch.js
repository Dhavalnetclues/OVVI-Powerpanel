var ValidateGlobalSearch = function() {

    var handleGlobalSearch = function() {
        $("#globalSearch_form").validate({
            errorElement: 'span', //default input error message container
            errorClass: 'help-block', // default input error message class
            ignore: [],
            rules: {
                globalSearch: {
                    required: true,
                    xssProtection: true,
                      check_special_char: true,
                    minlength: 3,
                    no_url: true
                }
            },
            messages: {
                globalSearch: {
                    required: "Please enter a value to search.",
                    xssProtection:"Please enter valid input.",
                    minlength: 'Please enter more then 3 character for search.'
                }
            },
            errorPlacement: function(error, element) {
                error.insertAfter(element.parent('.input-group'));
            },
            invalidHandler: function(event, validator) { //display error alert on form submit   
                $('.alert-danger', $('#globalSearch_form')).show();
            },
            highlight: function(element) { // hightlight error inputs
                $(element).closest('.form-control').addClass('has-error'); // set error class to the control group
            },
            unhighlight: function(element) {
                $(element).closest('.form-control').removeClass('has-error'); // set error class to the control group
            },
            submitHandler: function(form) {
                form.submit();
                return false;
            }
        });
        $('#globalSearch_form input').keypress(function(e) {
            if (e.which == 13) {
                if ($('#globalSearch_form').validate().form()) {
                    $('#globalSearch_form').submit();
                }
                return false;
            }
        });
    }
    return {
        //main function to initiate the module
        init: function() {
            handleGlobalSearch();
        }
    };
}();
jQuery(document).ready(function() {
ValidateGlobalSearch.init();
jQuery.validator.addMethod("xssProtection", function (value, element) {
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
$.validator.addMethod('no_url', function (value, element) {
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