/**
 * This method validates FAQs form fields
 * since   2016-12-24
 * author  NetQuick
 */
var Validate = function() {
    var handleEvents = function() {
        $("#frmEvents").validate({
            errorElement: 'span',
            errorClass: 'help-block',
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
                    noSpace: true
                },
                category_id: {
                    required: true,
                    noSpace: true
                },
                img_id: { required: false },
                short_description: {
                    required: true,
                    noSpace: true,
                    xssProtection: true,
                    no_url: true
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
                }
            },
            messages: {
                title: {
                    required: Lang.get('validation.required', {
                        attribute: Lang.get('template.title')
                    })
                },
                category_id: "Please select category",
                sector: "Please select Sector Type",
                short_description: { required: "Please enter short description" },
                varMetaTitle: {
                    required: Lang.get('validation.required', {
                        attribute: Lang.get('template.metatitle')
                    })
                },
                varMetaDescription: {
                    required: Lang.get('validation.required', {
                        attribute: Lang.get('template.metadescription')
                    })
                }
                // img_id: Lang.get('validation.required', { attribute: Lang.get('template.image') }),
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
            invalidHandler: function(event, validator) {
                var errors = validator.numberOfInvalids();
                if (errors) {
                    $.loader.close(true);
                }
                $('.alert-danger', $('#frmEvents')).show();
            },
            highlight: function(element) {
                $(element).closest('.form-group').addClass('has-error');
            },
            unhighlight: function(element) {
                $(element).closest('.form-group').removeClass('has-error');
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
        $('#frmEvents input').keypress(function(e) {
            if (e.which == 13 && e.keyCode != 188) {
                if ($('#frmEvents').validate().form()) {
                    $('#frmEvents').submit();
                }
                return false;
            }
        });
    }
    return {
        init: function() {
            handleEvents();
        }
    };
}();
jQuery(document).ready(function() {

    Validate.init();
    jQuery.validator.addMethod("noSpace", function(value, element) {
        if (value.trim().length <= 0) {
            return false;
        } else {
            return true;
        }
    }, "This field is required");

    // var imgIDs = $this.parents('.fileinput.fileinput-new').find("input[id^='start_date_time']").val();

    $("input[id^='start_date_time']").datepicker({
        autoclose: true,
        minuteStep: 5,
        format: DEFAULT_DT_FMT_FOR_DATEPICKER
    });

    $("input[id^='end_date_time']").datepicker({
        autoclose: true,
        minuteStep: 5,
        format: DEFAULT_DT_FMT_FOR_DATEPICKER
    });

    $("input[id^='start_date_time']").rules('add', {
        required: true
    });

    $("input[id^='end_date_time']").rules('add', {
        required: true
    });


    $("input[id^='timeSlotFrom']").rules('add', {
        required: true
    });

    $("input[id^='timeSlotTo']").rules('add', {
        required: true
    });

    $("input[id^='attendees0']").rules('add', {
        required: true
    });

    // $('#dateTimeSlot0 #start_date_time0').datepicker({
    //     autoclose: true,
    //     minuteStep: 5,
    //     format: DEFAULT_DT_FMT_FOR_DATEPICKER
    // });

    // $('#dateTimeSlot0 #end_date_time0').datepicker({
    //     autoclose: true,
    //     minuteStep: 5,
    //     format: DEFAULT_DT_FMT_FOR_DATEPICKER
    // });


    $("input[id^='timeSlotFrom']").datetimepicker({
        datepicker: false,
        format: 'H:i'
    });

    $("input[id^='timeSlotTo']").datetimepicker({
        datepicker: false,
        format: 'H:i'
    });

    var isChecked = true;
    // if (isChecked == 1) {
    //     $('.expdatelabel').removeClass('no_expiry');
    //     $('.expiry_lbl').text('Set Expiry');
    //     $(".expirydate").hide();
    //     $('#end_date_time').attr('disabled', 'disabled');
    // } else {
    //     $('.expdatelabel').addClass('no_expiry');
    //     $('.expiry_lbl').text('No Expiry');
    //     $('#end_date_time').removeAttr('disabled');
    // }

});

$(document).on("change", '#end_date_time', function() {
    $(this).attr('data-newvalue', $(this).val());
});

jQuery.validator.addMethod("daterange", function(value, element) {
    var fromDateTime = $('#start_date_time').val();
    var toDateTime = $("#end_date_time").val();
    var isChecked = $('#end_date_time').attr('data-exp');
    if (isChecked == 0) {
        toDateTime = new Date(toDateTime);
        fromDateTime = new Date(fromDateTime);
        return toDateTime >= fromDateTime && fromDateTime <= toDateTime;
    } else {
        return true;
    }
}, "The end date must be a greater than start date.");

jQuery.validator.addMethod("phoneFormat", function(value, element) {
    return this.optional(element) || /((\(\d{3}\) ?)|(\d{3}-))?\d{3}-\d{4}/.test(value);
}, 'Please enter a valid phone number.');

jQuery.validator.addMethod("noSpace", function(value, element) {
    if (value.trim().length <= 0) {
        return false;
    } else {
        return true;
    }
}, "This field is required");
jQuery.validator.addMethod("minStrict", function(value, element) {
    if (value > 0) {
        return true;
    } else {
        return false;
    }
}, 'Display order must be a number higher than zero');

jQuery.validator.addMethod("avoidonlyzero", function(value, element) {
    var newVal = value;
    if (newVal <= 0) {
        return false;
    } else {
        return true;
    }
}, "Please enter a valid value.");

$('input[type=text]').change(function() {
    var input = $(this).val();
    var trim_input = input.trim();
    if (trim_input) {
        $(this).val(trim_input);
        return true;
    }
});

// $('#noexpiry').click(function() {
//     var isChecked = $('#end_date_time').attr('data-exp');

//     if (isChecked == 0) {
//         $('.expdatelabel').removeClass('no_expiry');
//         $('.expiry_lbl').text('Set Expiry');
//         $('#end_date_time').attr('data-exp', '1');
//         $('#end_date_time').attr('disabled', 'disabled');
//         $(".expirydate").hide();
//         $("#end_date_time").val(null);
//         $('#end_date_time').val('');
//         $('.expirydate').next('span.help-block').html('');
//         $('.expirydate').parent('.form-group').removeClass('has-error');
//     } else {
//         $('.expdatelabel').addClass('no_expiry');
//         $('.expiry_lbl').text('No Expiry');
//         $('#end_date_time').attr('data-exp', '0');
//         $('#end_date_time').removeAttr('disabled');
//         $(".expirydate").show();
//         if ($('#end_date_time').attr('data-newvalue').length > 0) {
//             $("#end_date_time").val($('#end_date_time').attr('data-newvalue'));
//         } else {
//             $("#end_date_time").val('');
//         }
//     }
// });

function addDateTimeSlot(event, id) {
    nextId = parseInt(id) + 1;
    let current = document.querySelector('#' + event.parentNode.id);
    let nextSibling = current.nextElementSibling;

    if (nextSibling === null & $("#" + 'dateTimeSlot' + nextId).length == 0) {
        $('#' + event.parentNode.id).after('<div class="col-md-12" data-parentIndex="' + nextId + '" id="dateTimeSlot' + nextId + '"><div class="col-md-5"><div class="form-group form-md-line-input"><label class="control-label form_title">Start Date<span aria-required="true" class="required"> * </span></label><div class="input-group date form_meridian_datetime"><span class="input-group-btn date_default"><button class="btn date-set fromButton" type="button"><i class="ri-calendar-line"></i></button></span><input type="text" class="form-control" autocomplete="off"  name="start_date_time[' + parseInt(nextId) + '][startDate]"  id="start_date_time' + nextId + '"  placeholder="From" date("Y-m-d")></div><span class="help-block"></span></div></div><div class="col-md-5"><div class="form-group form-md-line-input"><div class="input-group date  form_meridian_datetime expirydate"><label class="control-label form_title" >End Date <span aria-required="true" class="required"> * </span></label><div class="pos_cal"><span class="input-group-btn date_default"><button class="btn date-set toButton" type="button"><i class="ri-calendar-line"></i></button></span><input type="text" class="form-control" autocomplete="off"  name="start_date_time[' + parseInt(nextId) + '][endDate]"  id="end_date_time' + nextId + '" onkeypress="javascript: return KeycheckOnlyDate(event);" onpaste="return false" placeholder="From" date("Y-m-d")></div></div><span class="help-block"></span></div></div><input type="button" name="Remove" value="Remove" class="btn red btn-outline" onclick="removeDateTimeSlot(this,' + nextId + ')" id="dateTimeSlotRemove' + nextId + '"><input type=button name="Add" value="Add" class="btn btn-green-drake" onclick="addDateTimeSlot(this,' + nextId + ')" id="dateTimeSlotAdd' + nextId + '"><div class="col-md-12"><lable class="control-label form_title">Time & Attendees:</lable><div class="col-md-12" id="timeSlot0" data-parentName="dateTimeSlot' + nextId + '"  data-index="0"><div class="col-md-3"><div class="form-group form-md-line-input"><input type="text" class="form-control" autocomplete="off" name="start_date_time[' + parseInt(nextId) + '][timeSlotFrom][]" id="timeSlotFrom0" placeholder="From"></div></div><div class="col-md-3"><div class="form-group form-md-line-input"><input type="text" class="form-control" autocomplete="off" name="start_date_time[' + parseInt(nextId) + '][timeSlotTo][]" id="timeSlotTo0" placeholder="To"></div></div><div class="col-md-3"><div class="form-group form-md-line-input"><input type="text" class="form-control" autocomplete="off" name="start_date_time[' + parseInt(nextId) + '][attendees][]" id="attendees0" placeholder="Please enter no of attendees"><span class="help-block"></span></div></div><input type="button" name="Add" value="Add" class="btn btn-green-drake" onclick="addTimeSlot(this,0,' + nextId + ')" id="timeSlotAdd' + nextId + '"></div></div></div>')


        $('#dateTimeSlot' + nextId + ' #start_date_time' + nextId).datepicker({
            autoclose: true,
            minuteStep: 5,
            format: DEFAULT_DT_FMT_FOR_DATEPICKER
        });

        $('#dateTimeSlot' + nextId + ' #end_date_time' + nextId).datepicker({
            autoclose: true,
            minuteStep: 5,
            format: DEFAULT_DT_FMT_FOR_DATEPICKER
        });

        $("#dateTimeSlotAdd" + parseInt(id)).remove();

        $('#dateTimeSlot' + nextId + ' #start_date_time' + nextId).rules('add', {
            required: true
        });
        $('#dateTimeSlot' + nextId + ' #end_date_time' + nextId).rules('add', {
            required: true
        });

        $('#dateTimeSlot' + nextId + ' #timeSlotFrom0').rules('add', {
            required: true
        });
        $('#dateTimeSlot' + nextId + ' #timeSlotTo0').rules('add', {
            required: true
        });

        $('#dateTimeSlot' + nextId + ' #attendees0').rules('add', {
            required: true
        });

        $("input[id^='timeSlotFrom']").datetimepicker({
            datepicker: false,
            format: 'H:i'
        });

        $("input[id^='timeSlotTo']").datetimepicker({
            datepicker: false,
            format: 'H:i'
        });


    }
}

function removeDateTimeSlot(event, id) {
    let current = document.querySelector('#' + event.parentNode.id);
    let prevSibling = document.querySelector('#' + $('#' + event.parentNode.id).prev().attr('id'));
    let nextSibling = document.querySelector('#' + $('#' + event.parentNode.id).next().attr('id'));
    if (nextSibling === null) {
        let dataIndex = parseInt($(prevSibling).data('parentindex'))
        $('#dateTimeSlot' + dataIndex).append('<input type="button" name="Add" value="Add" class="btn btn-green-drake" onclick="addDateTimeSlot(this,' + dataIndex + ')" id="dateTimeSlotAdd' + dataIndex + '">')
    }
    if (id === 0) {} else {
        if ($("#" + event.parentNode.id).length != 0) {
            $("#" + event.parentNode.id).remove();
        }
    }
}

function addTimeSlot(event, id, parentIndex) {

    nextId = parseInt(id) + 1;
    let current = document.querySelector('#' + event.parentNode.id);
    let nextSibling = current.nextElementSibling;

    if ($("#dateTimeSlot" + parentIndex + " #" + "timeSlot" + nextId).length == 0) {

        $('#dateTimeSlot' + parentIndex + ' #' + event.parentNode.id).after('<div class="col-md-12" id="timeSlot' + nextId + '" data-index="' + nextId + '"><div class="col-md-3"><div class="form-group form-md-line-input"><input type="text" class="form-control" autocomplete="off" name="start_date_time[' + parseInt(parentIndex) + '][timeSlotFrom][]" id="timeSlotFrom' + nextId + '" placeholder="From"></div></div><div class="col-md-3"><div class="form-group form-md-line-input"><input type="text" class="form-control" autocomplete="off" name="start_date_time[' + parseInt(parentIndex) + '][timeSlotTo][]" id="timeSlotTo' + nextId + '" placeholder="To"></div></div><div class="col-md-3"><div class="form-group form-md-line-input"><input type="text" class="form-control" autocomplete="off" name="start_date_time[' + parseInt(parentIndex) + '][attendees][]" id="attendees' + nextId + '" placeholder="Please enter no of attendees"></div></div><input type="button" name="Remove" value="Remove" class="btn red btn-outline" onclick="removeTimeSlot(this,' + nextId + ',' + parentIndex + ')" id="timeSlotRemove' + nextId + '"><input type="button" name="Add" value="Add" class="btn btn-green-drake" onclick="addTimeSlot(this,' + nextId + ',' + parentIndex + ')" id="timeSlotAdd' + nextId + '"></div>')

        // $('#timeSlotFrom' + nextId).each(function() {
        //     $(this).rules("add", {
        //         required: true,
        //         messages: {
        //             required: "From time Field is required",
        //         }
        //     });
        // });

        // $("input[id^='timeSlotTo']").each(function() {
        //     $(this).rules("add", {
        //         required: true,
        //         messages: {
        //             required: "To time Field is required",
        //         }
        //     });
        // });

        $('#dateTimeSlot' + parentIndex + ' #timeSlotFrom' + nextId).rules('add', {
            required: true
        });
        $('#dateTimeSlot' + parentIndex + ' #timeSlotTo' + nextId).rules('add', {
            required: true
        });
        $('#dateTimeSlot' + parentIndex + ' #attendees' + nextId).rules('add', {
            required: true
        });

        $('#dateTimeSlot' + parentIndex + " #timeSlotAdd" + parseInt(id)).remove();

        $("input[id^='timeSlotFrom']").datetimepicker({
            datepicker: false,
            format: 'H:i'
        });

        $("input[id^='timeSlotTo']").datetimepicker({
            datepicker: false,
            format: 'H:i'
        });
    }

}

function removeTimeSlot(event, id, parentIndex) {
    let current = document.querySelector('#' + event.parentNode.id);
    let nextSibling = document.querySelector('#' + $('#' + event.parentNode.id).next().attr('id'));
    let prevSibling = document.querySelector('#' + $('#' + event.parentNode.id).prev().attr('id'));
    if (nextSibling === null) {
        let dataIndex = parseInt($(prevSibling).data('index'))
        $("#dateTimeSlot" + parentIndex + ' #timeSlot' + dataIndex).append('<input type="button" name="Add" value="Add" class="btn btn-green-drake" onclick="addTimeSlot(this,' + dataIndex + ',' + parentIndex + ')" id="timeSlotAdd' + dataIndex + '">')
    }
    if (id === 0) {

    } else {
        if ($("#dateTimeSlot" + parentIndex + " #" + 'timeSlot' + parseInt(id)).length != 0) {
            $("#dateTimeSlot" + parentIndex + " #timeSlot" + parseInt(id)).remove();
        }
    }

}