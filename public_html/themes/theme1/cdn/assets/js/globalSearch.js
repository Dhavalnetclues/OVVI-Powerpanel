var ValidateGlobalSearch = function() {

    var handleGlobalSearch = function() {
        $("#globalSearch_form").validate({
            errorElement: 'span', //default input error message container
            errorClass: 'help-block', // default input error message class
            ignore: [],
            rules: {
                globalSearch: {
                    required: true,
                    minlength: 3,
                }
            },
            messages: {
                globalSearch: {
                    required: "Please enter a value to search.",
                    minlength: 'Please enter more then 3 character for search.'
                }
            },
            errorPlacement: function(error, element) {
                error.insertAfter(element);
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
                $("#emailtofriend_submit").attr("disabled", "disabled");
                form.submit();
                return false;
            }
        });
        $('#globalSearch_form input').keypress(function(e) {
            if (e.which == 13) {
                if ($('#globalSearch_form').validate().form()) {
                    $("#emailtofriend_submit").attr("disabled", "disabled");
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

$('#globalSearch_form input').keypress(function(e) {
    if (e.which == 13) {
        if ($('#globalSearch_form').validate().form()) {
            $("#searchbtn").attr("disabled", "disabled");
            form.submit();
            return false;
        }
        return false;
    }
});


ValidateGlobalSearch.init();