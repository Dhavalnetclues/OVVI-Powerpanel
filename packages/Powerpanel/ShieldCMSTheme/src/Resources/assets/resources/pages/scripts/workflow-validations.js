/**
 * This method validates service form fields
 * since   2016-12-24
 * author  NetQuick
 */
var Validate = function () {
    var handleWorkflow = function () {
        $("#frmWorkflow").validate({
            errorElement: 'span', //default input error message container
            errorClass: 'help-block', // default input error message class
            ignore: [],
            rules: {
                title: {
                    required: true
                },
                activity: {
                    required: true
                },
                action: {
                    required: true
                },
                after: {
                    required: true
                },
                frequancy_positive: {
                    required: true
                },
                frequancy_neagtive: {
                    required: true
                },
                after_content: {
                    required: true
                },
                yes_content: {
                    required: true
                },
                no_content: {
                    required: true
                }
            },
            messages: {
                title: Lang.get('validation.required', {
                    attribute: Lang.get('template.title')
                }),
                activity: {
                    required: 'Activity is required'
                },
                action: {
                    required: 'Action is required'
                },
                after: {
                    required: 'After is required'
                },
                frequancy_positive: {
                    required: 'Send e-mail is required'
                },
                frequancy_neagtive: {
                    required: 'Send reminder e-mail frequancy is required'
                },
                after_content: {
                    required: 'Email content is required'
                },
                yes_content: {
                    required: 'Email content is required'
                },
                no_content: {
                    required: 'Email content is required'
                }
            },
            errorPlacement: function (error, element) {
                if (element.parent('.input-group').length) {
                    error.insertAfter(element.parent());
                } else if (element.prop("tagName") == 'SELECT') {
                    error.insertAfter(element.parent().find('span:last'));
                } else {
                    error.insertAfter(element);
                }
            },
            invalidHandler: function (event, validator) { //display error alert on form submit
                var errors = validator.numberOfInvalids();
                if (errors) {
                    $.loader.close(true);
                }
                $('.alert-danger', $('#frmWorkflow')).show();
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
        $('#frmWorkflow input').keypress(function (e) {
            if (e.which == 13) {
                if ($('#frmWorkflow').validate().form()) {
                    $('#frmWorkflow').submit(); //form validation success, call ajax form submit
                }
                return false;
            }
        });
    };

    var handleWorkflowApprovals = function () {
        $("#frmWorkflowApproval").validate({
            errorElement: 'span', //default input error message container
            errorClass: 'help-block', // default input error message class
            ignore: [],
            rules: {
                category_id: {
                    required: true
                },
                'submission_type[]': {
                    required: true
                },
                'catwise_modules[]': {
                    required: {
                        depends: function () {
                            return $("#undo_redo option").length > 0;
                        }
                    }
                },
                user_roles: {
                    required: true
                },
                'user[]': {
                    required: {
                        depends: function () {
                            return $("select[name='catwise_modules[]'] option:selected").length > 0;
                        }
                    }
                }
            },
            messages: {

                category_id: {
                    required: 'Category is required'
                },
                'submission_type[]': {
                    required: 'When is required'
                },
                'catwise_modules[]': {
                    required: 'Module is required'
                },
                user_roles: {
                    required: 'Role is required'
                },
                'user[]': {
                    required: 'Admin is required'
                }
            },
            errorPlacement: function (error, element) {
                if (element.parent('.input-group').length) {
                    error.insertAfter(element.parent());
                } else if (element.attr("name") == "catwise_modules[]") {
                    error.appendTo("#errorToShow");
                } else if (element.prop("tagName") == 'SELECT') {
                    error.insertAfter(element.parent().find('span:last'));
                } else {
                    error.insertAfter(element);
                }
            },
            invalidHandler: function (event, validator) { //display error alert on form submit
                var errors = validator.numberOfInvalids();
                if (errors) {
                    $.loader.close(true);
                }
                $('.alert-danger', $('#frmWorkflowApproval')).show();
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
        $('#frmWorkflowApproval input').keypress(function (e) {
            if (e.which == 13) {
                if ($('#frmWorkflowApproval').validate().form()) {
                    $('#frmWorkflowApproval').submit(); //form validation success, call ajax form submit
                }
                return false;
            }
        });
    };
    return {
        //main function to initiate the module
        init: function () {
            handleWorkflow();
            handleWorkflowApprovals();
        }
    };
}();
jQuery(document).ready(function () {
    Validate.init();
    jQuery.validator.addMethod("noSpace", function (value, element) {
        if (value.trim().length <= 0) {
            return false;
        } else {
            return true;
        }
    }, "This field is required");

    jQuery.validator.addMethod("minStrict", function (value, element) {
        // allow any non-whitespace characters as the host part
        if (value > 0) {
            return true;
        } else {
            return false;
        }
    }, 'Display order must be a number higher than zero');

    jQuery.validator.addMethod("checkWfExists", function (value, element) {
        value = getWfExists($('#category_id').val(), $('#user_roles').val());
        if (value > 0) {
            return false;
        } else {
            return true;
        }
    }, 'Workflow with same role and category already exists.');

    $('#type').change(function () {
        if ($(this).val() == 'approvals') {
            $('.leads').addClass('hide');
            $('.approvals').removeClass('hide');
        } else if ($(this).val() == 'leads') {
            $('.leads').removeClass('hide');
            $('.approvals').addClass('hide');
        }

        $('#user option[value=""]').removeAttr('selected');
        $('#user').select2({
            placeholder: "Select Admin",
            width: '100%'
        });

        $('#submission_type option[value=""]').removeAttr('selected');
        $('#submission_type').select2({
            placeholder: "Select When",
            width: '100%'
        });

        $('#user_roles option[value=""]').removeAttr('selected');
        $('#user_roles').select2({
            placeholder: "Select Role",
            width: '100%'
        });

        $('#catwise_modules option[value=""]').removeAttr('selected');
        $('#catwise_modules').select2({
            placeholder: "",
            width: '100%'
        });

    });
    getAdminAjax($('#category_id').val());
    getCategoryAjax($('#user_roles').val());
//		getCategoryWiseModule($('#category_id').val(),$('#user_roles').val());

    $('#user').change(function () {
        $('#user option[value=""]').removeAttr('selected');
    });

    $('#submission_type').change(function () {
        $('#submission_type option[value=""]').removeAttr('selected');
    });

    $('#user_roles').change(function () {
        $('#user_roles option[value=""]').removeAttr('selected');
    });

    $('#catwise_modules').change(function () {
        $('#catwise_modules option[value=""]').removeAttr('selected');
    });

    $(document).on('change', '#category_id', function () {
        var groupid = $(this).val();
        var categoryIds = $(this).val();
        var roleIds = $('#user_roles').val();
        getCategoryWiseModule(categoryIds, roleIds);
        getAdminAjax(groupid);

    });
});

$('#approval-yes-no').on('switchChange.bootstrapSwitch', function (event, state) {
    var switchState = $(this).bootstrapSwitch('state');
    if (switchState) {
        $('.approval-yes').removeClass('hide');
        if (!$('.approval-no').hasClass('hide')) {
            $('.approval-no').addClass('hide');
            $('#user').prop('disabled', false);
        }
    } else {
        $('.approval-no').removeClass('hide');
        if (!$('.approval-yes').hasClass('hide')) {
            $('.approval-yes').addClass('hide');
            $('#user').prop('disabled', true);
        }
    }

    $('#user option[value=""]').removeAttr('selected');
    $('#user').select2({
        placeholder: "Select Admin",
        width: '100%'
    });

});


function getAdminAjax(groupid) {
    $.ajax({
        type: "POST",
        url: window.site_url + '/powerpanel/workflow/get-admin',
        data: {
            groupId: groupid
        },
        async: false,
        success: function (data) {
            $('#user').html(data);
        },
        complete: function () {
            var userids = $('#user').data('selected');
            if (userids != true) {
                var selectedIds = userids.split(',');
                $('#user').val(selectedIds);
                $('#user').trigger('change.select2');
            }

        }
    });
}

function getWfExists(catId, roleId) {
    var response = 0;
    $.ajax({
        type: "POST",
        url: window.site_url + '/powerpanel/workflow/check-wfexists',
        data: {
            category_id: catId,
            user_roles: roleId
        },
        async: false,
        success: function (data) {
            response = JSON.parse(data);
            if (typeof response.id != "undefined") {
                response = response.id;
            }
        }
    });
    return response;
}

$(document).on('change', '#user_roles', function () {
    var user_role = $(this).val();
    getCategoryAjax(user_role);
});


function getCategoryAjax(user_role) {
    var catSelected = $('#category_id').data('selected');
    $.ajax({
        type: "POST",
        url: window.site_url + '/powerpanel/workflow/get-category',
        data: {
            role: user_role,
            selected: catSelected
        },
        async: false,
        success: function (data) {
            $('#category_id').html(data);
        },
        complete: function () {
            var selectedIds = $('#category_id').data('selected');
            $('#category_id').val(selectedIds);
            $('#category_id').trigger('change.select2');
        }
    });
}

function getCategoryWiseModule(categoryIds, roleIds) {
    var moduleSelected = $('#catwise_modules').data('selected');
    $.ajax({
        type: "POST",
        url: window.site_url + '/powerpanel/workflow/get-modulebycategory',
        data: {
            category_id: categoryIds,
            role_id: roleIds,
            selected: moduleSelected
        },
        async: false,
        success: function (data) {
            $("#modulehtml").show();
            $('#undo_redo').html(data);
            $('#undo_redo_to option').each(function () {
                $(this).remove();
            });
        },
        complete: function () {
            var selectedIds = $('#catwise_modules').data('selected');
            $('#undo_redo').val(selectedIds);
            $('#undo_redo').trigger('change.select2');
        }
    });
}