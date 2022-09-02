/**
 * This method validates cms pages form fields
 * since   2016-12-24
 * author  NetQuick
 */
var Validate = function() {
    var handleCmsPage = function() {
        $("#frmCmsPage").validate({
            errorElement: 'span', //default input error message container
            errorClass: 'help-block', // default input error message class
            ignore: [],
            rules: {
                title: {
                    required: true,
                    noSpace: true,
                    xssProtection: true,
                    no_url: true
                },
                sector: {
                    required: true,
                },
                module: {
                    required: true,
                    noSpace: true
                },
                varMetaTitle: {
                    required: true,
                    noSpace: true,
                    xssProtection: true,
                    no_url: true
                },
                varMetaDescription: {
                    required: true,
                    noSpace: true,
                    xssProtection: true,
                    no_url: true
                },
                start_date_time: {
                    required: true,
                },
                end_date_time: {
                    daterange: true,
                    required: {
                        depends: function() {
                            var isChecked = $('#cmspage_end_date').attr('data-exp');
                            if (isChecked == 0) {
                                return $('input[name=end_date_time]').val().length == 0;
                            }
                        }
                    }
                },
                'new-alias': {
                    specialCharacterCheck: true,
                },
                // new_password: {
                //     required: {
                //         depends: function() {
                //             if ($('input[name="chrPageActive"][value="PP"]').prop("checked") == true) {
                //                 return true;
                //             } else {
                //                 return false;
                //             }
                //         }
                //     },
                //     passwordrules: {
                //         depends: function() {
                //             if ($('input[name="chrPageActive"][value="PP"]').prop("checked") == true) {
                //                 return true;
                //             } else {
                //                 return false;
                //             }
                //         }
                //     },
                //     minlength: 6,
                //     maxlength: 20
                // },
            },
            messages: {
                title: {
                    required: Lang.get('validation.required', { attribute: Lang.get('template.title') }),
                },
                sector: {
                    required: 'Sector field is required.',
                },
                module: Lang.get('validation.required', { attribute: Lang.get('template.selectmodule') }),
                varMetaTitle: {
                    required: Lang.get('validation.required', { attribute: Lang.get('template.metatitle') }),
                },
                varMetaDescription: {
                    required: Lang.get('validation.required', { attribute: Lang.get('template.metadescription') }),
                },
                start_date_time: {
                    required: "Start date field is required.",
                },
                end_date_time: {
                    required: Lang.get('validation.required', { attribute: Lang.get('template.enddate') }),
                    daterange: 'The end date must be a greater than start date.'
                },
                // new_password: {
                //     required: Lang.get('validation.required', { attribute: 'Password' }),
                //     passwordrules: 'Please follow rules for password.'
                // },
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
                console.log(validator.numberOfInvalids() + " field(s) are invalid");
                if (errors) {
                    $.loader.close(true);
                }
                $('.alert-danger', $('#frmCmsPage')).show();
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
                $("button[type='submit']").attr('disabled', 'disabled');
                return false;
            }
        });
        $('#tags-input input').on('keypress', function(e) {
            if (e.keyCode == 13) {
                e.keyCode = 188;
                e.preventDefault();
            };
        });
        $('#frmCmsPage input').keypress(function(e) {
            if (e.which == 13 && e.keyCode != 188) {
                if ($('#frmCmsPage').validate().form()) {
                    $('#frmCmsPage').submit(); //form validation success, call ajax form submit
                }
                return false;
            }
        });
    }
    return {
        //main function to initiate the module
        init: function() {
            handleCmsPage();
        }
    };
}();


jQuery(document).ready(function() {
    Validate.init();
    ValidateSharePage.init();

    var isChecked = $('#end_date_time').attr('data-exp');
    if (isChecked == 1) {
        $('.expdatelabel').removeClass('no_expiry');
        $('.expiry_lbl').text('Set Expiry');
        $(".expirydate").hide();
        $('#end_date_time').attr('disabled', 'disabled');
    } else {
        $('.expdatelabel').addClass('no_expiry');
        $('.expiry_lbl').text('No Expiry');
        $('#end_date_time').removeAttr('disabled');
    }

});

// $('.fromButton').click(function () {
//     $('#start_date_time').datetimepicker('show');
// });
// $('.toButton').click(function () {
//     $('#end_date_time').datetimepicker('show');
// });
$('#start_date_time').flatpickr({
    dateFormat: DEFAULT_DATE_FORMAT + " H:i",
    minDate: 'today',
    enableTime: true
});
$('#end_date_time').flatpickr({
    dateFormat: DEFAULT_DATE_FORMAT + " H:i",
    minDate: 'today',
    enableTime: true
});
$('#start_date_time').on('change', function (e) {
    let index = e.target.getAttribute("data-dateIndex");
    let sdate = new Date(e.target.value)
    $('#end_date_time').flatpickr({
        dateFormat: DEFAULT_DATE_FORMAT + " H:i",
        minDate: sdate,
    enableTime: true
    }).clear();
});
//disable start-date
// $('#end_date_time').on('change', function (e) {
//     let index = e.target.getAttribute("data-dateIndex");
//     let date = new Date(e.target.value)
//     $('#start_date_time').flatpickr({
//         dateFormat: DEFAULT_DATE_FORMAT,
//         minDate: 'today',
//         maxDate: date
//     }).clear();
// });
$(document).on("change", '#end_date_time', function () {
    $(this).attr('data-newvalue', $(this).val());
});
$('#noexpiry').click(function () {
    var isChecked = $('#end_date_time').attr('data-exp');

    if (isChecked == 0) {
        $('.expdatelabel').removeClass('no_expiry');
        $('.expiry_lbl').text('Set Expiry');
        $('#end_date_time').attr('data-exp', '1');
        $('#end_date_time').attr('disabled', 'disabled');
        $(".expirydate").hide();
        $("#end_date_time").val(null);
        $('#end_date_time').val('');
        $('.expirydate').next('span.help-block').html('');
        $('.expirydate').parent('.form-group').removeClass('has-error');
    } else {
        $('.expdatelabel').addClass('no_expiry');
        $('.expiry_lbl').text('No Expiry');
        $('#end_date_time').attr('data-exp', '0');
        $('#end_date_time').removeAttr('disabled');
        $(".expirydate").show();
        if ($('#end_date_time').attr('data-newvalue').length > 0) {
            $("#end_date_time").val($('#end_date_time').attr('data-newvalue'));
        } else {
            $("#end_date_time").val('');
        }
    }
});

$('input[name=title]').change(function() {
    var title = $(this).val();
    var trim_title = title.trim();
    if (trim_title) {
        $(this).val(trim_title);
        return true;
    }
});

jQuery.validator.addMethod("daterange", function(value, element) {
    var fromDateTime = $('#cmspage_start_date').val();
    var toDateTime = $("#cmspage_end_date").val();
    var isChecked = $('#cmspage_end_date').attr('data-exp');
    if (isChecked == 0) {
        toDateTime = new Date(toDateTime);
        fromDateTime = new Date(fromDateTime);
        return toDateTime >= fromDateTime && fromDateTime <= toDateTime;
    } else {
        return true;
    }
}, "The end date must be a greater than start date.");

jQuery.validator.addMethod("noSpace", function(value, element) {
    if (value.trim().length <= 0) {
        return false;
    } else {
        return true;
    }
}, "This field is required");

jQuery.validator.addMethod("phoneFormat", function(value, element) {
    // allow any non-whitespace characters as the host part
    return this.optional(element) || /((\(\d{3}\) ?)|(\d{3}-))?\d{3}-\d{4}/.test(value);
}, 'Please enter a valid phone number.');

function showModuleWiseBlocks(modId) {
    if(modId != undefined && modId != '') {
        $('.nav-tabs li').each(function() {
            var dialogid = $(this).data('moduleid');
            if (dialogid == modId) {
                $(this).show();
                // $(this).parent('ul.nav-tabs').find('li[data-moduleid='+modId+']').addClass('active');
                // var tabName = $(this).parent('ul.nav-tabs').find('li[data-moduleid='+modId+']').attr('id');
                // var tabId = tabName.split('_');
                // $('#'+tabId[0]).addClass('active');
            } else {
                $(this).hide();
            }
        });   
    }
    $(this).removeClass('active');
    $('.tab-pane').removeClass('active');
    $('#blocks_tab').show();
    $('#bootstrap_tab').show();
    $('#blocks_tab').addClass('active');
    $('#blocks').addClass('active');
}

$(document).on('click', '#sharepage', function (e) {
    e.preventDefault();
    
    let pageActive = $("input[name='chrPageActive']:checked").val();
    let alias = $('.alias').text();
    let aliasField = $('.aliasField').val();
    let sectorType = $('#varSector').val();
    let privateLink = $('#privateLink').val();
    let href = '';
    
    if (pageActive == 'PP') {
        $("#password_div").show();
        href = alias;
    } else {
        $("#password_div").hide();
        $('#newpassword').val('');
        href = alias + '/' + privateLink;
    }
    
    $('#seo_link').attr('href', href);
    $('#seo_link').text(alias);
    $('#seoLink').val(alias);
    $('#aliasId').val(aliasField);
    $('#sectorType').val(sectorType);
    $('#pageActive').val(pageActive);
    $('#sharepageModel').modal('show');
});

var ValidateSharePage = function () {
    var handleSharePageToFrm = function () {
        $("#sharePageForm").validate({
            errorElement: 'span', //default input error message container
            errorClass: 'help-block error', // default input error message class
            ignore: [],
            rules: {
                email: {
                    required: true,
                    email: true,
                    noSpace: true,
                    no_url: true
                },
                password: {
                    required: {
                        depends: function() {
                            if ($('input[name="chrPageActive"][value="PP"]').prop("checked") == true) {
                                return true;
                            } else {
                                return false;
                            }
                        }
                    },
                    passwordrules: {
                        depends: function() {
                            if ($('input[name="chrPageActive"][value="PP"]').prop("checked") == true) {
                                return true;
                            } else {
                                return false;
                            }
                        }
                    },
                    minlength: 6,
                    maxlength: 20
                },
            },
            messages: {
                email: {
                    required: "Please enter the email.",
                    email: "Please enter valid email",
                    no_url: "URL doesn't allowed"
                },
                password: {
                    required: Lang.get('validation.required', { attribute: 'Password' }),
                    passwordrules: 'Please follow rules for password.'
                },
            },
            errorPlacement: function (error, element) {
                if (element.attr('id') == 'g-recaptcha-response-1') {
                    error.insertAfter(element.parent());
                } else {
                    error.insertAfter(element);
                }
            },
            invalidHandler: function (event, validator) { //display error alert on form submit
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
                $("#share_submit").prop("disabled", true);
                sharesubmit();
                return false;
            }
        });
        $('#sharePageForm input').keypress(function (e) {
            if (e.which == 13) {
                if ($('#sharePageForm').validate().form()) {
                    $("#share_submit").prop("disabled", true);
                    sharesubmit();
                }
                return false;
            }
        });
    }
    return {
        //main function to initiate the module
        init: function () {
            handleSharePageToFrm();
        }
    };
}();

function sharesubmit() {
    if ($("#sharePageForm").valid()) {
        var frmData = $('#sharePageForm').serialize();
        jQuery.ajax({
            type: "POST",
            url: sharePageURL,
            data: frmData,
            dataType: 'json',
            async: true,
            success: function (data) {
                $("#share_submit").prop("disabled", false);
                setTimeout(function () {
                    $('.alert').hide()
                }, 5000);
                if (data.success == 1) {
                    $('#shareSuccess').text(data.msg);
                    $('#shareSuccess').show();
                    $('#shareError').hide();
                    $('#sharepageModel').modal('hide');
                } else {
                    $('#shareSuccess').hide();
                    $('#shareError').text(data.msg);
                    $('#shareError').show();
                    $('#sharepageModel').modal('hide');
                }
            }
        });
    }
}