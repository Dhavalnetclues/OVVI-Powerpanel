/**
 * This method validates Role form fields
 * since   2016-12-24
 * author  NetQuick
 */

var modalMenuItemId;
var Validate = function() {
    var handleRole = function() {
        $("#frmRole").validate({
            errorElement: 'span', //default input error message container
            errorClass: 'help-block', // default input error message class
            ignore: [],
            rules: {
                name: {
                    required: true,
                    noSpace: true,
                    xssProtection: true,
                    no_url: true
                },
                sector: {
                    required: true,
                },
                display_name: {
                    required: true,
                    noSpace: true
                },
                permission: "required"
            },
            messages: {
                name: {
                    requied: "Name is required"
                },
                sector: {
                    requied: "Sector is required"
                },
                display_name: "Display name is required",
                permission: "Permission is required"

            },
            errorPlacement: function(error, element) {
                if (element.parent('.input-group').length) {
                    error.insertAfter(element.parent());
                } else if (element.hasClass('choices__input')) {
                    error.insertAfter(element.parent().parent().next('span'));
                } else {
                    error.insertAfter(element);
                }
            },
            invalidHandler: function(event, validator) { //display error alert on form submit
                var errors = validator.numberOfInvalids();
                if (errors) {
                    $.loader.close(true);
                }
                $('.alert-danger', $('#frmRole')).removeClass('d-none');
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
        $('#frmRole input').keypress(function(e) {
            if (e.which == 13) {
                if ($('#frmRole').validate().form()) {
                    $('#frmRole').submit(); //form validation success, call ajax form submit
                }
                return false;
            }
        });
    }
    return {
        //main function to initiate the module
        init: function() {
            handleRole();
        }
    };
}();

function checks(el){
	let checked = $(el).find('.permitip:checked').length;
  let checkCount = $(el).find('.permitip').length;
  if(checkCount == 0){
  	$(el).remove();
  }else if(checked == checkCount){
  	$(el).find('.per-check-all').prop('checked',true);
  }else{
  	$(el).find('.per-check-all').prop('checked',false);
  }
}



jQuery(document).ready(function() {
    Validate.init();
    hideActions();

    $('.permit-row').each(function() {
       checks(this); 
    });

    $('.permitip').change(function(){
    	checks($(this).parent().parent().parent());
    });

    $('.per-check-all').change(function(){
    	let elm = $(this).parent().parent().parent();
    	if($(this).is(':checked')){
    		elm.find('.permitip').prop('checked',true);
    	}else{
    		elm.find('.permitip').prop('checked',false);
    	}
    });

    if (!editing) {
        /*$('.banners .per_edit input[type=checkbox]').trigger('click');
        $('.banners .per_list input[type=checkbox]').trigger('click');
        $('.pages .per_edit input[type=checkbox]').trigger('click');
        $('.pages .per_list input[type=checkbox]').trigger('click');*/
    }

    jQuery.validator.addMethod("noSpace", function(value, element) {
        if (value.trim().length <= 0) {
            return false;
        } else {
            return true;
        }
    }, "This field is required");

    $.each($('.grp-sec .module-activation'), function() {
        if ($(this).prop('checked')) {
            $(this).closest('.grp-sec').find('.group-activation').prop('checked', true);
        }
    });

});
jQuery.validator.addMethod("phoneFormat", function(value, element) {
    // allow any non-whitespace characters as the host part
    return this.optional(element) || /((\(\d{3}\) ?)|(\d{3}-))?\d{3}-\d{4}/.test(value);
}, 'Please enter a valid phone number.');
$('input[type=text]').change(function() {
    var input = $(this).val();
    var trim_input = input.trim();
    if (trim_input) {
        $(this).val(trim_input);
        return true;
    }
});

var prevent = false;
var grpPrevent = false;
$('.module-activation').on('change', function(event, state) {
    $('#frmRole').loader(loaderConfig);
    var switchState = $(this).prop('checked');
    if (switchState) {
        if (!prevent) {
            $(this).parent().parent().parent().parent().parent().find('.right_permis input[type=checkbox]:visible').prop('checked', true);
        }
    } else {
        $(this).parent().parent().parent().parent().parent().find('.right_permis input[type=checkbox]:visible').prop('checked', false);
        prevent = false;
    }

    if ($(this).parent().parent().parent().parent().parent().parent().parent().find('.module-activation:checked').length > 0) {
        $(this).parent().parent().parent().parent().parent().parent().parent().parent().find('.group-activation').prop('checked', true);
    } else {
        $(this).parent().parent().parent().parent().parent().parent().parent().parent().find('.group-activation').prop('checked', false);
    }
    grpPrevent = false;

    // if($('.per_add input[type=checkbox]').is(':visible') == false)
    // {
    // 	$('.per_add input[type=checkbox]:checked').removeAttr('checked');
    // }
    setTimeout(function() {
        $.loader.close(true);
    }, 1000);
});



$('.group-activation').on('change', function(event, state) {
    $('#frmRole').loader(loaderConfig);
    var switchState = $(this).prop('checked');
    if (switchState) {
        if (grpPrevent) {
            $(this).parent().parent().parent().parent().parent().find('.right_permis input[type=checkbox]').prop('checked', true);
            $(this).parent().parent().parent().parent().parent().find('.activation input[type=checkbox]').prop('checked', true);
        }
    } else {
        $(this).parent().parent().parent().parent().parent().find('.right_permis input[type=checkbox]').prop('checked', false);
        $(this).parent().parent().parent().parent().parent().find('.activation input[type=checkbox]').prop('checked', false);
        grpPrevent = true;
    }

    // if($('.per_add input[type=checkbox]').is(':visible') == false)
    // {
    // 	$('.per_add input[type=checkbox]:checked').removeAttr('checked');
    // }

    setTimeout(function() {
        $.loader.close(true);
    }, 1000);
});


$('.right_permis input[type=checkbox]').on('click', function(event, state) {
    if ($(this).parent().parent().children().children('input[type=checkbox]:checked').length < 1) {
        $(this).parent().parent().parent().find('.module-activation').prop('checked', false);
        prevent = false;
    } else {
        prevent = true;
        $(this).parent().parent().parent().find('.module-activation').prop('checked', true);
    }

    if ($(this).parent().parent().parent().parent().parent().find('.module-activation:checked').length < 1) {
        $(this).parent().parent().parent().parent().parent().parent().find('.group-activation').prop('checked', false);

    } else {

        $(this).parent().parent().parent().parent().parent().parent().find('.group-activation').prop('checked', true);
    }
    grpPrevent = false;

});

$('#isadmin').on('change', function(event, state) {
    $('.per_delete input[type=checkbox]:checked').removeAttr('checked');
    $('.per_publish input[type=checkbox]:checked').removeAttr('checked');
    if (!editing && state) {
        $('.banners .per_add input[type=checkbox]').trigger('click');
        $('.pages .per_add input[type=checkbox]').trigger('click');
    }
    if (editing) {
        editHandleActions();
    }
    hideActions();
});

function hideActions() {
    if (!$('#isadmin').prop('checked')) {
        $('.per_delete').addClass('d-none');
        $('.per_delete input[type=checkbox]').attr('disabled', true);
        $('.per_publish').addClass('d-none');
        $('.per_publish input[type=checkbox]').attr('disabled', true);
        // $('.per_add').addClass('d-none');
        // $('.per_add input[type=checkbox]').attr('disabled',true);
        $('.user-management').closest('.grp-sec').addClass('d-none');
        $('.user-management input[type=checkbox]').prop('checked', false);
        // $('.per_add input[type=checkbox]:checked').parent().removeClass('d-none');
        // $('.per_add input[type=checkbox]:checked').removeAttr('disabled');

        if (!editing) {
            $(".module-activation").prop('checked', false);
            prevent = true;

            $('.banners .per_edit input[type=checkbox]').trigger('click');
            $('.banners .per_list input[type=checkbox]').trigger('click');

            $('.pages .per_edit input[type=checkbox]').trigger('click');
            $('.pages .per_list input[type=checkbox]').trigger('click');

            prevent = false;
        }
    } else {
        $('.per_delete').removeClass('d-none');
        $('.per_delete input[type=checkbox]').removeAttr('disabled');
        $('.per_publish').removeClass('d-none');
        $('.per_publish input[type=checkbox]').removeAttr('disabled');
        // $('.per_add').removeClass('d-none');
        // $('.per_add input[type=checkbox]').removeAttr('disabled',true);
        $('.user-management').closest('.grp-sec').removeClass('d-none');

        if (!editing) {
            prevent = false;
            $(".module-activation").prop('checked', true);
            $(".module-activation").trigger('change');
            $('.banners .per_delete input[type=checkbox]').trigger('click');
            $('.banners .per_publish input[type=checkbox]').trigger('click');

            $('.pages .per_delete input[type=checkbox]').trigger('click');
            $('.pages .per_publish input[type=checkbox]').trigger('click');
        }
    }
}

function editHandleActions() {
    if (!$('#isadmin').prop('checked')) {
        $(".module-activation").prop('checked', false);
        prevent = true;
        $(".module-activation").trigger('change');
        prevent = false;
    } else {
        prevent = false;
        $(".module-activation").prop('checked', true);
        $(".module-activation").trigger('change');
        $(".module-activation").each(function(index) {
            $(this).parent().parent().parent().parent().parent().find('.right_permis input[type=checkbox]').prop('checked', true);
        });
    }
}