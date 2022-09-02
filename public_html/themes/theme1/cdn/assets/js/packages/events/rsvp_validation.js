var eventData;
var eventId;

function eventInfo(data, id) {
    this.eventData = JSON.parse(data);
    this.eventId = id
    $('#eventId').val(id)

    $('#event_date').children().remove();

    var $mySelect = $('#event_date');
    $.each(eventData, function(key, value) {
        var $option = $("<option/>", {
            value: JSON.stringify(value),
            text: value.startDate + ' to ' + value.endDate
        });
        $mySelect.append($option);
    });

    var $timeSelect = $('#event_time');

    $.each(eventData[0].timeSlotFrom, function(key, value) {
        var $option = $("<option/>", {
            value: JSON.stringify(value),
            text: value + ' to ' + eventData[0].timeSlotTo[key]
        });
        $timeSelect.append($option);
    });

    $('.selectpicker').selectpicker('refresh');

}

function KeycheckOnlyPhonenumber(e) {
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
    if (r >= 65 && r <= 90 || r >= 97 && r <= 122 || r >= 33 && r <= 39 || r >= 42 && r <= 42 || r >= 44 && r <= 44 || r >= 46 && r <= 47 || r >= 58 && r <= 64 || r >= 91 && r <= 96 || r >= 123 && r <= 126) {
        return false
    }
    return true
}

var Validate = function() {
    console.log('event', eventData)
    var handleContact = function() {

        $("#eventRSVP_form").validate({
            errorElement: 'span', //default input error message container
            errorClass: 'help-block error', // default input error message class
            ignore: [],
            rules: {
                event_date: {
                    required: true,
                },
                event_time: {
                    required: true,
                },
                no_of_attendee: {
                    required: true,
                },
                phoneno: {
                    minlength: 6,
                    maxlength: 20,
                    phonenumber: {
                        required: false,
                    }
                },
                "g-recaptcha-response": {
                    required: true
                },
                message: {
                    badwordcheck: true,
                    messageValidation: true,
                    xssProtection: true,
                    no_url: true
                },
            },
            messages: {
                event_date: {
                    required: "Event Date is required.",
                },
                event_time: {
                    required: "Event Time is required.",
                },
                no_of_attendee: {
                    maxlength: "Please select no of attendee",
                },
                "g-recaptcha-response": {
                    required: "Captcha is required.",
                },
            },
            errorPlacement: function(error, element) {
                if (element.attr('id') == 'g-recaptcha-response-1') {
                    error.insertAfter(element.parent());
                } else if (element.attr("name") == "category") {
                    error.insertAfter(element.parent());
                } else {
                    error.insertAfter(element);
                }
            },
            invalidHandler: function(event, validator) { //display error alert on form submit   
                $('.alert-danger', $('#eventRSVP_form')).show();
            },
            highlight: function(element) { // hightlight error inputs
                $(element).closest('.form-group').addClass('has-error'); // set error class to the control group
            },
            unhighlight: function(element) {
                $(element).closest('.form-group').removeClass('has-error'); // set error class to the control group
            },
            submitHandler: function(form) {
                $("#contact_submit").attr("disabled", "disabled");
                SetBackGround();
                grecaptcha.execute();
                form.submit();
                return false;
            }
        });
        $('#eventRSVP_form input').keypress(function(e) {
            if (e.which == 13) {
                if ($('#eventRSVP_form').validate().form()) {
                    $("#contact_submit").attr("disabled", "disabled");
                    SetBackGround();
                    $('#eventRSVP_form').submit(); //form validation success, call ajax form submit
                }
                return false;
            }
        });
    }
    return {
        //main function to initiate the module
        init: function() {
            handleContact();
        }
    };
}();

function hiddenCallBack() {
    document.getElementById("cont").submit();
}
jQuery(document).ready(function() {
    Validate.init();

    $("input[id^='full_name']").rules('add', {
        required: true
    });

    $("input[id^='email']").rules('add', {
        required: true,
        emailFormat: true,
        badwordcheck: true
    });

    $.validator.addMethod("phonenumber", function(value, element) {
        var numberPattern = /\d+/g;
        var newVal = value.replace(/\D/g);
        if (parseInt(newVal) <= 0) {
            return false;
        } else {
            return true;
        }
    }, 'Please enter a valid phone number.');

    var blacklist = /\b(nude|naked|sex|porn|porno|sperm|fuck|penis|pussy|vagina|boobs|asshole|shit|bitch|motherfucker|dick|orgasm|fucker)\b/; /* many more banned words... */
    $.validator.addMethod("badwordcheck", function(value) {
        return !blacklist.test(value.toLowerCase());
    }, "Please remove bad word/inappropriate language.");
    $.validator.addMethod("minimum_length", function(value, element) {
        if ($("#phone_no").val().length < 5 || $("#phone_no").val().length > 20) {
            return false;
        } else {
            return false;
        }
    }, 'Please enter a phone number minimum 5 digits and maximum 20 digits.');
    jQuery.validator.addMethod("noSpace", function(value, element) {
        if (value.trim().length <= 0) {
            return false;
        } else {
            return true;
        }
    }, "No space please and don't leave it empty");
    jQuery.validator.addMethod("emailFormat", function(value, element) {
        // allow any non-whitespace characters as the host part
        return this.optional(element) || /^[_A-Za-z0-9-]+(\.[_A-Za-z0-9-]+)*@[A-Za-z0-9-]+(\.[A-Za-z0-9-]+)*(\.[A-Za-z]{2,4})$/.test(value);
    }, 'Enter valid email format');
    jQuery.validator.addMethod("messageValidation", function(value, element) {
        // allow any non-whitespace characters as the host part
        return this.optional(element) || /<(\w+)((?:\s+\w+(?:\s*=\s*(?:(?:"[^"]*")|(?:'[^']*')|[^>\s]+))?)*)\s*(\/?)>/.test(value) == false ? true : false;
    }, 'Enter valid message');
    jQuery.validator.addMethod("xssProtection", function(value, element) {
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
    $.validator.addMethod('no_url', function(value, element) {
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
    $('input[name=email]').change(function() {
        var email = $(this).val();
        var trim_email = email.trim();
        if (trim_email) {
            $(this).val(trim_email);
            return true;
        }
    });
});


$("#event_date").change(function(event) {
    let data = JSON.parse(event.target.value)
    $('#event_time').children().remove();

    var $mySelect = $('#event_time');
    $.each(data.timeSlotFrom, function(key, value) {

        if (data.attendees[key] > data.attendeeRegistered[key]) {
            var $option = $("<option/>", {
                value: value + '_' + data.timeSlotTo[key],
                text: value + ' to ' + data.timeSlotTo[key]
            });
            $mySelect.append($option);
        }

    });

    $('.selectpicker').selectpicker('refresh');
});

$("#no_of_attendee").change(function(event) {
    console.log('val', event.target.value)
    var ones = ['', 'First', 'Second', 'Third', 'Fourth', 'Fifth'];
    $('#attendeList').html('')
    for (let index = 0; index < event.target.value; index++) {
        let attendee = '<div class="row" id="attendeList' + index + '"><div class="col-lg-4 col-sm-6"><div class="form-group ac-form-group"><label class="ac-label" for="firstName">Name <span class="star">*</span></label><input type="text" class="form-control" autocomplete="off" name="attendee[' + index + '][full_name]" id="full_name' + index + '" placeholder="' + ones[index] + ' Attendees Name"><span class="help-block"></span></div></div><div class="col-lg-4 col-sm-6"><div class="form-group ac-form-group"><label class="ac-label" for="email">Email <span class="star">*</span></label><input type="email" class="form-control" autocomplete="off" name="attendee[' + index + '][email]" id="email' + index + '" placeholder="' + ones[index] + ' Attendees Email"><span class="help-block"></span></div></div><div class="col-lg-4 col-sm-6"><div class="form-group ac-form-group"><label class="ac-label" for="email">Phone</label><input type="number" class="form-control" autocomplete="off" name="attendee[' + index + '][phone]" id="phone' + index + '" placeholder="' + ones[index] + ' Attendees Phone"><span class="help-block"></span></div></div></div>'

        $("#attendeList").append(attendee);

        $("#full_name" + index).rules('add', {
            required: true
        });

        $("#email" + index).rules('add', {
            required: true,
            emailFormat: true,
            badwordcheck: true
        });
    }



})