/**
 * This method validates blog form fields
 * since   2016-12-24
 * author  NetQuick
 */

var Custom = (function() {
    return {
        //main function
        init: function() {
            //initialize here something.
        },

        checkVersion: function() {
            var radioValue = $("input[name='chrDisplay']:checked").val();

            if (radioValue == "on") {

                $(".displaydropdown").addClass("hide");

            } else {

                $(".displaydropdown").removeClass("hide");
                $('#modules').select2({
                    placeholder: "Select Module",
                    width: '100%',
                    minimumResultsForSearch: 5
                });

            }

        },

        getModuleRecords: function(moduleName, modelName) {
            var ajaxUrl = site_url + "/powerpanel/popup/selectRecords";

            jQuery.ajax({
                type: "POST",
                url: ajaxUrl,
                dataType: "HTML",
                data: {
                    module: moduleName,
                    model: modelName,
                    selected: selectedRecord,
                    useraction: user_action,
                },
                async: false,
                success: function(result) {
                    $("#foritem").html(result).select2({
                        placeholder: "Select Module",
                        width: "100%",
                        minimumResultsForSearch: 5,
                    });
                },
            });
        },
    };
})();

var Validate = (function() {
    var handleBlog = function() {
        $("#frmPopup").validate({
            errorElement: "span", //default input error message container
            errorClass: "help-block", // default input error message class
            ignore: [],
            rules: {
                title: { required: true, noSpace: true },
                start_date_time: { required: true },
                end_date_time: {
                    daterange: {
                        required: {
                            depends: function() {
                                var isChecked = $("#popup_end_date").attr("data-exp");
                                if (isChecked == 0) {
                                    return $("input[name=end_date_time]").val().length > 0;
                                }
                            },
                        },
                    },
                },
                modules: {
                    required: {
                        depends: function() {
                            var radioValue = $("input[name='chrDisplay']:checked").val();

                            if (radioValue == "on") {
                                return false;
                            } else {
                                return true;
                            }
                        },
                    },
                    noSpace: {
                        depends: function() {
                            var radioValue = $("input[name='chrDisplay']:checked").val();

                            if (radioValue == "on") {
                                return false;
                            } else {
                                return true;
                            }
                        },
                    },
                },
                foritem: {
                    required: {
                        depends: function() {
                            var radioValue = $("input[name='chrDisplay']:checked").val();

                            if (radioValue == "on") {
                                return false;
                            } else {
                                return true;
                            }
                        },
                    },
                    noSpace: {
                        depends: function() {
                            var radioValue = $("input[name='chrDisplay']:checked").val();

                            if (radioValue == "on") {
                                return false;
                            } else {
                                return true;
                            }
                        },
                    },
                },
            },
            messages: {
                title: Lang.get("validation.required", {
                    attribute: Lang.get("template.title"),
                }),
                start_date_time: Lang.get("validation.required", {
                    attribute: Lang.get("template.managePopup.startDateTime"),
                }),
                end_date_time: Lang.get("validation.required", {
                    attribute: Lang.get("template.managePopup.endDateTime"),
                }),
                modules: Lang.get("validation.required", {
                    attribute: Lang.get("template.module"),
                }),
                foritem: Lang.get("validation.required", {
                    attribute: Lang.get("template.page"),
                }),
            },
            errorPlacement: function(error, element) {
                if (element.parent(".input-group").length) {
                    error.insertAfter(element.parent());
                } else if (element.hasClass("select2")) {
                    error.insertAfter(element.next("span"));
                } else {
                    error.insertAfter(element);
                }
            },
            invalidHandler: function(event, validator) {
                //display error alert on form submit
                var errors = validator.numberOfInvalids();
                if (errors) {
                    $.loader.close(true);
                }
                $(".alert-danger", $("#frmPopup")).show();
            },
            highlight: function(element) {
                // hightlight error inputs
                $(element).closest(".form-group").addClass("has-error"); // set error class to the control group
            },
            unhighlight: function(element) {
                $(element).closest(".form-group").removeClass("has-error"); // set error class to the control group
            },
            submitHandler: function(form) {
                $("body").loader(loaderConfig);
                form.submit();
                return false;
            },
        });
        $("#frmPopup input").on("keypress", function(e) {
            if (e.which == 13) {
                if ($("#frmPopup").validate().form()) {
                    $("#frmPopup").submit(); //form validation success, call ajax form submit
                }
                return false;
            }
        });
    };
    return {
        //main function to initiate the module
        init: function() {
            handleBlog();
        },
    };
})();

jQuery(document).ready(function() {
    Custom.init();
    Validate.init();

    Custom.checkVersion();

    $(document).on("click", ".versionradio", function(e) {
        Custom.checkVersion();
    });
    jQuery.validator.addMethod(
        "noSpace",
        function(value, element) {
            if (!$(element).hasClass("select2")) {
                value = value.trim();
            }
            if (value.length <= 0) {
                return false;
            } else {
                return true;
            }
        },
        "This field is required"
    );
    var isChecked = $("#popup_end_date").attr("data-exp");
    if (isChecked == 1) {
        $(".expdatelabel").removeClass("no_expiry");
        $(".expiry_lbl").text("Set Expiry");
        $(".expirydate").hide();
        $("#popup_end_date").attr("disabled", "disabled");
    } else {
        $(".expdatelabel").addClass("no_expiry");
        $(".expiry_lbl").text("No Expiry");
        $("#popup_end_date").prop("disabled", false);
    }

    $("#modules").on("change", function(e) {
        Custom.getModuleRecords(
            $("#modules option:selected").data("module"),
            $("#modules option:selected").data("model")
        );
    });
});
jQuery(document).ready(function() {
    $('#popup_start_date').datetimepicker({
        format: DEFAULT_DATE_FORMAT + ' ' + DEFAULT_TIME_FORMAT,
        onShow: function() {
            this.setOptions({})
        },
        scrollMonth: false,
        scrollInput: false
    });

    $('#popup_end_date').datetimepicker({
        format: DEFAULT_DATE_FORMAT + ' ' + DEFAULT_TIME_FORMAT,
        onShow: function() {
            this.setOptions({})
        },
        scrollMonth: false,
        scrollInput: false
    });
});
jQuery(document).ready(function() {
    if (selectedRecord > 0) {
        $("#modules").trigger("change");

        $("#modules").select2({
            placeholder: "Select Module",
            width: "100%",
            minimumResultsForSearch: 5,
        });
        $("#records").show();
    }
});
jQuery.validator.addMethod(
    "phoneFormat",
    function(value, element) {
        // allow any non-whitespace characters as the host part
        return (
            this.optional(element) ||
            /((\(\d{3}\) ?)|(\d{3}-))?\d{3}-\d{4}/.test(value)
        );
    },
    "Please enter a valid phone number."
);

jQuery.validator.addMethod(
    "minStrict",
    function(value, element) {
        // allow any non-whitespace characters as the host part
        if (value > 0) {
            return true;
        } else {
            return false;
        }
    },
    "Display order must be a number higher than zero"
);

$("input[type=text]").on("change", function() {
    var input = $(this).val();
    var trim_input = input.trim();
    if (trim_input) {
        $(this).val(trim_input);
        return true;
    }
});

jQuery.validator.addMethod(
    "daterange",
    function(value, element) {
        var fromDateTime = $("#popup_start_date").val();
        var toDateTime = $("#popup_end_date").val();
        var isChecked = $("#popup_end_date").attr("data-exp");
        if (isChecked == 0) {
            toDateTime = new Date(toDateTime);
            fromDateTime = new Date(fromDateTime);
            return toDateTime >= fromDateTime && fromDateTime < toDateTime;
        } else {
            return true;
        }
        // var fromDate=moment(fromDateTime, ["YYYY-M-D h:mm A"]).format("YYYY-M-D HH:mm");
        // var toDate=moment(toDateTime, ["YYYY-M-D h:mm A"]).format("YYYY-M-D HH:mm");
        //return (toDate < fromDate);
    },
    "The end date & time must be a date after start date & time."
);

$(".fromButton").on("click", function() {
    $("#popup_start_date").datetimepicker("show");
});
$(".toButton").on("click", function() {
    $("#popup_end_date").datetimepicker("show");
});

$(document).on("change", "#popup_end_date", function() {
    $(this).attr("data-newvalue", $(this).val());
});

$("#noexpiry").on("click", function() {
    var isChecked = $("#popup_end_date").attr("data-exp");

    if (isChecked == 0) {
        $(".expdatelabel").removeClass("no_expiry");
        $(".expiry_lbl").text("Set Expiry");
        $("#popup_end_date").attr("data-exp", "1");
        $("#popup_end_date").attr("disabled", "disabled");
        $(".expirydate").hide();
        $("#popup_end_date").val(null);
        $("#popup_end_date").val("").datetimepicker("update");
        $(".expirydate").next("span.help-block").html("");
        $(".expirydate").parent(".form-group").removeClass("has-error");
    } else {
        $(".expdatelabel").addClass("no_expiry");
        $(".expiry_lbl").text("No Expiry");
        $("#popup_end_date").attr("data-exp", "0");
        $("#popup_end_date").prop("disabled", false);
        $(".expirydate").show();
        if ($("#popup_end_date").attr("data-newvalue").length > 0) {
            $("#popup_end_date").val($("#popup_end_date").attr("data-newvalue"));
        } else {
            $("#popup_end_date").val();
        }
    }
});