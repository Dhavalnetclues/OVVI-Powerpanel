
var Validate = function () {
    var handlePolling = function () {
        $("#polling_form").validate({
            errorElement: 'span', //default input error message container
            errorClass: 'help-block error', // default input error message class
            ignore: [],
            rules: {

                "g-recaptcha-response": {
                    required: true
                },

            },
            messages: {
                "g-recaptcha-response": {
                    required: "Please select I'm not a robot."
                }
            },
            errorPlacement: function (error, element) {
                if (element.attr('id') == 'g-recaptcha-response-1') {
                    error.insertAfter(element.parent());
                } else if (element.attr('id') == 'department') {
                    error.insertAfter(element.parent());
                } else {
                    error.insertAfter(element);
                }
            },
            invalidHandler: function (event, validator) { //display error alert on form submit   
                $('.alert-danger', $('#polling_form')).show();
            },
            highlight: function (element) { // hightlight error inputs
                $(element).closest('.form-group').addClass('has-error'); // set error class to the control group
            },
            unhighlight: function (element) {
                $(element).closest('.form-group').removeClass('has-error'); // set error class to the control group
            },
            submitHandler: function (form) {
                $("#contact_submit").attr("disabled", "disabled");
                SetBackGround();
                grecaptcha.execute();
                form.submit();
                return false;
            }
        });
        $('#polling_form input').keypress(function (e) {
            if (e.which == 13) {
                if ($('#polling_form').validate().form()) {
                    $("#contact_submit").attr("disabled", "disabled");
                    SetBackGround();
                    $('#polling_form').submit(); //form validation success, call ajax form submit
                }
                return false;
            }
        });
    }
    return {
        //main function to initiate the module
        initpolling: function () {
            handlePolling();
        }
    };
}();
function hiddenCallBack() {
    document.getElementById("cont").submit();
}
jQuery(document).ready(function () {
    Validate.initpolling();
});
