/**
 * This method show hides default banner fields
 * since   2016-12-22
 * author  NetQuick
 */
var Custom = function() {
    return {
        //main function
        init: function() {
            //initialize here something.
        },
        checkType: function() {
            var radioValue = $("input[name='banner_type']:checked").val();
            if (radioValue == 'inner_banner') {
                $('#pages').show();
                if ($('#modules').val() != ' ') {
                    $('#records').show();
                }
                if ($('.banner_image_img').html('<img class="img_opacity" src=" ' + CDN_PATH + 'resources/images/upload_file.gif"" />')) {
                    $('#overflowhome').hide();
                }
                $('#DisplayVideo').hide();
                $('#txtshortdesc').hide();
                $('#linkTEXT').hide();
                $('#DisplayLink').hide();
                $('#banner_image').val('');
                $('#hideimg').val('');
                $('#home_banner_img').hide();
                $('#inner_banner_img').show();
                $('#HomeBannerSize').hide();
                $('#InnerBannerSize').show();
                $('.imguploader').show();
                $('.iconuploader').hide();
                $('.bannerversion').hide();
                $('#Links').hide();
                $("input[name='defaultBanner']").removeAttr('disabled');
                $("input[name='defaultBanner']").closest('.form-group').show();
                document.getElementById('videolink').value = "";
                $('#chrDisplayVideo').prop('checked', false);
                $("#VideoLinkTEXT").css("display", "none");
                $("#txtshortdesc").css("display", "block");
                $("#DisplayLink").css("display", "block");
                $("#linkTEXT").css("display", "block");
                // $('#modules').select2({
                //     placeholder: "Select Module",
                //     width: '100%',
                //     minimumResultsForSearch: 5
                // });
            } else if (radioValue == 'home_banner') {
                $('#pages').hide();
                $('#Links').show();
                $('#records').hide();
                $('#txtshortdesc').show();
                $('#linkTEXT').show();
                $('#home_banner_img').show();
                $('#innerbanner_image').val('');
                $('#inner_banner_img').hide();
                $('#DisplayLink').show();
                $('#DisplayVideo').show();
                $('#HomeBannerSize').show();
                $('.iconuploader').show();
                $('#InnerBannerSize').hide();
                $('.bannerversion').hide();
                if ($('.innerbanner_image_img').html('<img class="img_opacity" src=" ' + CDN_PATH + 'resources/images/upload_file.gif"" />')) {
                    $('#overflowinner').hide();
                }
                Custom.checkVersion();
                $("input[name='defaultBanner']").attr('disabled', true);
                $("input[name='defaultBanner']").closest('.form-group').hide();
            }
        },
        checkVersion: function() {
            var radioValue = $("input[name='bannerversion']:checked").val();
            if (radioValue == 'img_banner') {
                $('.imguploader').show();
                $('.viduploader').addClass('hide');
            } else {
                $('.imguploader').hide();
                $('.viduploader').removeClass('hide');
            }
            // $('#modules').select2({
            //     placeholder: "Select Module",
            //     width: '100%',
            //     minimumResultsForSearch: 5
            // });
        },
        getModuleRecords: function(modelId) {
            var ajaxUrl = site_url + '/powerpanel/banners/selectRecords';
            jQuery.ajax({
                type: "POST",
                url: ajaxUrl,
                dataType: 'HTML',
                data: { "id": modelId },
                async: false,
                beforeSend: function() {
                    choicesEl['foritem'].destroy();
                },
                success: function(result) {
                    $('#records').show();
                    $("#foritem").html(result).trigger('change');
                },
                complete:function(){
                    let element = document.getElementById('foritem');
                    const choices = new Choices(element);
                    choicesEl['foritem'] = choices;
                }
            });
        }
    }
}();
/**
 * This method validates banner form fields
 * since   2016-12-24
 * author  NetQuick
 */
var Validate = function() {
    var handleBanner = function() {
        $("#frmBanner").validate({
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
                varRotateTime: {
                    required: true,
                    noSpace: true,
                    no_url: true,
                    xssProtection: true,
                    number: true,
                },
                //description: "required",
                display_order: {
                    required: true,
                    minStrict: true,
                    number: true,
                    noSpace: true,
                    xssProtection: true,
                    no_url: true
                },
                img_id: {
                    required: {
                        depends: function() {
                            if ($('#home_banner').prop('checked') == true) {
                                return true;
                            }
                        }
                    }
                },
                img_id_inner: {
                    required: {
                        depends: function() {
                            if ($('#inner_banner').prop('checked') == true) {
                                return true;
                            }
                        }
                    }
                },
                img_id_icon: {
                    required: {
                        depends: function() {
                            if ($('#home_banner').prop('checked') == true) {
                                return true;
                            }
                        }
                    }
                },
                videolink: {
                    required: {
                        depends: function() {
                            if ($('#chrDisplayVideo').prop('checked') == true) {
                                return true;
                            }
                        }
                    },
                    urlvalidate: true,
                },
                video_id: {
                    required: {
                        depends: function() {
                            if ($('#vid_banner').prop('checked') == true) {
                                return true;
                            }
                        }
                    }
                },
                modules: {
                    required: {
                        depends: function() {
                            if ($('#inner_banner').prop('checked') == true) {
                                return true;
                            }
                        }
                    },
                    noSpace: {
                        depends: function() {
                            if ($('#inner_banner').prop('checked') == true) {
                                return true;
                            }
                        }
                    }
                },
                foritem: {
                    required: {
                        depends: function() {
                            if ($('#inner_banner').prop('checked') == true) {
                                return true;
                            }
                        }
                    },
                    noSpace: {
                        depends: function() {
                            if ($('#inner_banner').prop('checked') == true) {
                                return true;
                            }
                        }
                    }
                },
                start_date_time: {
                    required: true,
                },
                link: {
                    url: true,
                    xssProtection: true,
                },
                end_date_time: {
                    daterange: true,
                    required: {
                        depends: function() {
                            var isChecked = $('#banner_end_date').attr('data-exp');
                            if (isChecked == 0) {
                                return $('input[name=end_date_time]').val().length == 0;
                            }
                        }
                    }
                },
                short_description: {
                    xssProtection: true,
                    no_url: true
                }
            },
            messages: {
                title: {
                    required: "Please enter the title.",
                },
                img_id: "Please select the banner.",
                img_id_icon: {
                    required: "Please upload the icon.",
                },
                img_id_inner: {
                    required: "Please select the inner banner."
                },
                modules: "Please select the module.",
                foritem: "Please select the page.",
                videolink: {
                    required: "Please enter the url.",
                    urlvalidate: "Only youtube & vimeo embed video url supported.",
                },
                varRotateTime: {
                    required: "Please enter the image rotate time.",
                },
                link: {
                    url: "Please enter the valid url.",
                },
                display_order: {
                    required: "Please enter the display order",
                    minStrict: Lang.get('validation.minStrict', { attribute: Lang.get('template.displayorder') }),
                    number: Lang.get('validation.number', { attribute: Lang.get('template.displayorder') }),
                    noSpace: Lang.get('validation.noSpace', { attribute: Lang.get('template.displayorder') })
                },
                start_date_time: {
                    required: "Please enter the start date.",
                },
                end_date_time: {
                    required: Lang.get('validation.required', { attribute: Lang.get('template.enddate') }),
                    daterange: 'The end date must be a greater than start date.'
                },
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
                $('.alert-danger', $('#frmBanner')).show();
            },
            highlight: function(element) { // hightlight error inputs
                $(element).closest('.form-group').addClass('has-error'); // set error class to the control group
            },
            unhighlight: function(element) {
                $(element).closest('.form-group').removeClass('has-error'); // set error class to the control group
            },
            submitHandler: function(form) { // for demo
                $('body').loader(loaderConfig);
                form.submit();
                $("button[type='submit']").attr('disabled', 'disabled');
                return false;
            }
        });
        $('#frmBanner input').keypress(function(e) {
            if (e.which == 13) {
                if ($('#frmBanner').validate().form()) {
                    $('#frmBanner').submit(); //form validation success, call ajax form submit
                }
                return false;
            }
        });
    }
    return {
        //main function to initiate the module
        init: function() {
            handleBanner();
        }
    };
}();
jQuery(document).ready(function() {
    Custom.init();
    Custom.checkType();
    Custom.checkVersion();
    Validate.init();
    $(document).on('click', '.versionradio', function(e) {
        Custom.checkVersion();
    });
    $(document).on('click', '.banner', function(e) {
        Custom.checkType();
    });
    $('#modules').on("change", function(e) {
        Custom.getModuleRecords($("#modules").val());
    });
    jQuery.validator.addMethod("noSpace", function(value, element) {
        if (value.trim().length <= 0) {
            return false;
        } else {
            return true;
        }
    }, "This field is required");
    var isChecked = $('#banner_end_date').attr('data-exp');
    if (isChecked == 1) {
        $('.expdatelabel').removeClass('no_expiry');
        $('.expiry_lbl').text('Set Expiry');
        $(".expirydate").hide();
        $('#banner_end_date').attr('disabled', 'disabled');
    } else {
        $('.expdatelabel').addClass('no_expiry');
        $('.expiry_lbl').text('No Expiry');
        $('#banner_end_date').removeAttr('disabled');
    }
    
});
// jQuery(document).ready(function() {
//     $('#banner_start_date').datetimepicker({
//         format: DEFAULT_DATE_FORMAT + ' ' + DEFAULT_TIME_FORMAT,
//         onShow: function() {
//             this.setOptions({})
//         },
//         scrollMonth: false,
//         scrollInput: false
//     });

//     $('#banner_end_date').datetimepicker({
//         format: DEFAULT_DATE_FORMAT + ' ' + DEFAULT_TIME_FORMAT,
//         onShow: function() {
//             this.setOptions({})
//         },
//         scrollMonth: false,
//         scrollInput: false
//     });
// });
jQuery.validator.addMethod("urlvalidate", function(value, element) {
    if (value != "") {
        if ((/((http|https):\/\/)?(www\.)?(youtube\.com)(\/)?([a-zA-Z0-9\-\.]+)\/?/.test(value) == true) || (/^(http\:\/\/|https\:\/\/)?(www\.)?(vimeo\.com\/)([0-9]+)$/.test (value) == true) || (/((http|https):\/\/)?(player\.)?(vimeo\.com)(\/)?([a-zA-Z0-9\-\.]+)\/?/.test(value) == true) ) {
            return true;
        } else {
            return false;
        }
    } else {
        return true;
    }
}, '<div class="fl" id="chrVideoTypeY"><?php echo "Please enter a youtube link."; ?></div>');
jQuery.validator.addMethod("phoneFormat", function(value, element) {
    // allow any non-whitespace characters as the host part
    return this.optional(element) || /((\(\d{3}\) ?)|(\d{3}-))?\d{3}-\d{4}/.test(value);
}, 'Please enter a valid phone number.');
jQuery.validator.addMethod("minStrict", function(value, element) {
    // allow any non-whitespace characters as the host part
    if (value > 0) {
        return true;
    } else {
        return false;
    }
}, 'Display order must be a number higher than zero');
jQuery.validator.addMethod("noSpace", function(value, element) {
    if (value.trim().length <= 0) {
        return false;
    } else {
        return true;
    }
}, "This field is required");
$('input[name=title]').change(function() {
    var title = $(this).val();
    var trim_title = title.trim();
    if (trim_title) {
        $(this).val(trim_title);
        return true;
    }
});
$(window).load(function() {
    var radioValue = $("input[name='banner_type']:checked").val();
    if (selectedRecord > 0) {
        $('#modules').trigger('change');
        if (radioValue == 'home_banner') {
            // $('#modules').select2({
            //     placeholder: "Select Module",
            //     width: '100%',
            //     minimumResultsForSearch: 5
            // });
            $('#records').hide();
        }
    }
});
jQuery.validator.addMethod("daterange", function(value, element) {
    var fromDateTime = $('#banner_start_date').val();
    var toDateTime = $("#banner_end_date").val();
    var isChecked = $('#banner_end_date').attr('data-exp');
    if (isChecked == 0) {
        toDateTime = new Date(toDateTime);
        fromDateTime = new Date(fromDateTime);
        return toDateTime >= fromDateTime && fromDateTime <= toDateTime;
    } else {
        return true;
    }
}, "The end date must be a greater than start date.");
// $('.fromButton').click(function() {
//     $('#banner_start_date').datetimepicker('show');
// });
// $('.toButton').click(function() {
//     $('#banner_end_date').datetimepicker('show');
// });
$(document).on("change", '#banner_end_date', function() {
    $(this).attr('data-newvalue', $(this).val());
});
$('#noexpiry').click(function() {
    var isChecked = $('#banner_end_date').attr('data-exp');
    if (isChecked == 0) {
        $('.expdatelabel').removeClass('no_expiry');
        $('.expiry_lbl').text('Set Expiry');
        $('#banner_end_date').attr('data-exp', '1');
        $('#banner_end_date').attr('disabled', 'disabled');
        $(".expirydate").hide();
        $("#banner_end_date").val(null);
        $('#banner_end_date').val('');
        $('.expirydate').next('span.help-block').html('');
        $('.expirydate').parent('.form-group').removeClass('has-error');
    } else {
        $('.expdatelabel').addClass('no_expiry');
        $('.expiry_lbl').text('No Expiry');
        $('#banner_end_date').attr('data-exp', '0');
        $('#banner_end_date').removeAttr('disabled');
        $(".expirydate").show();
        if ($('#banner_end_date').attr('data-newvalue').length > 0) {
            $("#banner_end_date").val($('#banner_end_date').attr('data-newvalue'));
        } else {
            // $("#banner_end_date").val(oldVal);
            $("#banner_end_date").val();
        }
    }
});
$('#chrDisplayVideo').click(function() {
    var chrsection = $(this).prop("checked");
    if (chrsection == true) {
        $("#VideoLinkTEXT").css("display", "block");
        $("#txtshortdesc").css("display", "none");
        $("#DisplayLink").css("display", "none");
        $("#linkTEXT").css("display", "none");
    } else {
        $("#VideoLinkTEXT").css("display", "none");
        $("#txtshortdesc").css("display", "block");
        $("#DisplayLink").css("display", "block");
        $("#linkTEXT").css("display", "block");
        document.getElementById('videolink').value = "";
    }
});
jQuery(document).ready(function() {
    var chrsection = $('#chrDisplayVideo').prop('checked');
    if (chrsection == true) {
        $("#VideoLinkTEXT").css("display", "block");
        $("#txtshortdesc").css("display", "none");
        $("#DisplayLink").css("display", "none");
        $("#linkTEXT").css("display", "none");
    } else {
        $("#VideoLinkTEXT").css("display", "none");
        $("#txtshortdesc").css("display", "block");
        $("#DisplayLink").css("display", "block");
        $("#").css("display", "block");
        document.getElementById('videolink').value = "";
    }
});
$('#banner_start_date').flatpickr({
    dateFormat: DEFAULT_DATE_FORMAT + " H:i",
    minDate: 'today',
    enableTime: true
});
$('#banner_end_date').flatpickr({
    dateFormat: DEFAULT_DATE_FORMAT + " H:i",
    minDate: 'today',
    enableTime: true
});
$('#banner_start_date').on('change', function (e) {
    let index = e.target.getAttribute("data-dateIndex");
    let sdate = new Date(e.target.value)
    $('#banner_end_date').flatpickr({
        dateFormat: DEFAULT_DATE_FORMAT + " H:i",
        minDate: sdate,
    enableTime: true
    }).clear();
});