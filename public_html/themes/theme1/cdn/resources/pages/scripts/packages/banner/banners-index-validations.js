/**
 * This method validates banner form fields
 * since   2017-01-27
 * author  NetQuick
 */
var Validate = function () {
    var handleBanner = function () {
        $("#frmshareoption").validate({
            errorElement: 'span', //default input error message container
            errorClass: 'help-block', // default input error message class
            rules: {
                varTitle: "required",
                txtDescription: "required",
                'socialmedia[]': "required",
            },
            messages: {
                varTitle: {
                    required: 'Please enter title.'
                },
                txtDescription: {
                    required: 'Please enter description.'
                },
                "socialmedia[]": {
                    required: 'Please select any social media.'
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
                $('.alert-danger', $('#frmshareoption')).show();
            },
            highlight: function (element) { // hightlight error inputs
                $(element).closest('.form-group').addClass('has-error'); // set error class to the control group
            },
            unhighlight: function (element) {
                $(element).closest('.form-group').removeClass('has-error'); // set error class to the control group
            },
            submitHandler: function (form) { // for demo
                var postdata = $("#frmshareoption").serialize();
                $.ajax({
                    type: "POST",
                    url: onePushShare,
                    data: postdata,
                    async: false,
                    success: function (result) {
                        console.log(result);
                        // window.location.reload();
                    }
                });
                return false;
            }
        });

        $('#frmshareoption input').keypress(function (e) {
            if (e.which == 13) {
                if ($('#frmshareoption').validate().form()) {
                    $('#frmshareoption').submit(); //form validation success, call ajax form submit
                }
                return false;
            }
        });
    }

    return {
        //main function to initiate the module
        init: function () {
            handleBanner();
        }

    };

}();

jQuery(document).ready(function () {
    Validate.init();
});