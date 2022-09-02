function Quickeditfun(id, title, intSearchRank, startDate, endDate, value) {
    $("#quickedit").attr('value', value);
    if (endDate == 'No Expiry' || endDate == '') {
        $('#end_date_time').attr('data-exp', '1');
        $('.expdatelabel').removeClass('no_expiry');
        $('.expiry_lbl').text('Set Expiry');
        $(".expirydate").hide();
        $('#end_date_time').attr('disabled', 'disabled');
    } else {
        $('#end_date_time').attr('data-exp', '0');
        $(".expirydate").show();
        $('.expdatelabel').addClass('no_expiry');
        $('.expiry_lbl').text('No Expiry');
        $('#end_date_time').removeAttr('disabled');
    }
    if (intSearchRank == '1') {
        $('#yes_radio').prop('checked', true);
    }
    if (intSearchRank == '2') {
        $('#maybe_radio').prop('checked', true);
    }
    if (intSearchRank == '3') {
        $('#no_radio').prop('checked', true);
    }
    $("#id").val(id);
    $("#name").val(title);
    $("#start_date_time").val(startDate);
    $("#end_date_time").val(endDate);
    $("#modalForm").modal('show');
    //    var today = moment.tz(DEFAULT_TIME_ZONE).format(DEFAULT_DT_FORMAT + " H:m:s");
    //    $('#start_date_time').datetimepicker({
    //        autoclose: true,
    //        startDate: today,
    //        showMeridian: true,
    //        minuteStep: 5,
    //        format: DEFAULT_DT_FMT_FOR_DATEPICKER + ' HH:ii P'
    //    }).on("changeDate", function (e) {
    //        $("#start_date_time").closest('.has-error').removeClass('has-error');
    //        $("#start_date_time-error").remove();
    //        var startdate = moment($('#start_date_time').val());
    //        startdate = moment(startdate).add(1, 'hours').format(DEFAULT_DT_FORMAT + " H:m:s");
    //        $('#end_date_time').datetimepicker('setStartDate', startdate);
    //    });
    //    var startdate = moment($('#start_date_time').val());
    //    startdate = moment(startdate).add(1, 'hours').format(DEFAULT_DT_FORMAT + " H:m:s");
    //    $('#end_date_time').datetimepicker({
    //        autoclose: true,
    //        startDate: startdate,
    //        showMeridian: true,
    //        minuteStep: 5,
    //        format: DEFAULT_DT_FMT_FOR_DATEPICKER + ' HH:ii P'
    //    }).on("changeDate", function (e) {
    //        $("#end_date_time").closest('.has-error').removeClass('has-error');
    //        $("#end_date_time-error").remove();
    //    });
}
var Quick_URL = window.site_url + "/powerpanel/Quickedit_Listing";

function submitQuickedit() {
    var value = document.getElementById("quickedit").value;
    var id = $("#id").val();
    var name = $("#name").val();
    var search_rank = $("input[name='search_rank']:checked").val();
    var start_date_time = $("#start_date_time").val();
    var end_date_time = $("#end_date_time").val();
    if (end_date_time == 'No Expiry' || end_date_time == '') {
        var end_date_time = '';
    } else {
        var end_date_time = $("#end_date_time").val();
    }
    $.ajax({
        type: 'POST',
        url: Quick_URL,
        data: 'id=' + id + '&name=' + name + '&search_rank=' + search_rank + '&start_date_time=' + start_date_time + '&end_date_time=' + end_date_time + '&value=' + value + '&moduleid=' + Quick_module_id,
        success: function(msg) {
            $('#modalForm').hide();
            $('#Approved .approveMsg').text("The record has been successfully updated.");
            $('#Approved').show();
            // $('#Approved').modal({
            //     backdrop: 'static',
            //     keyboard: false
            // });
            $("#Approved").modal("show");
            $(document).on('click', '#ApprovedSuccess', function() {
                $(".close").trigger('click');
                var x = location.href;
                window.location.href = x + "?tab=" + value;
            });
        }
    });
}

function Trashfun(id) {
    $('#Approve .approveMsg').text("Are you sure you want to trash this record on the website? Click on Yes to confirm.");
    $('#Approve1').show();
    // $('#Approve').modal({
    //     backdrop: 'static',
    //     keyboard: false
    // });
    $("#Approve").modal("show");
    var TRASH_URL = window.site_url + "/powerpanel/TrashData_Listing";
    $(document).on('click', '#Approve1', function() {
        // $('#Approve').modal('hide');
        // $('body').loader(loaderConfig);
        $.ajax({
            type: 'POST',
            url: TRASH_URL,
            data: 'id=' + id + '&moduleid=' + Quick_module_id,
            success: function(msg) {
                $("#Approve").modal("hide");
                // $(".close").trigger('click');
                // $.loader.close(true);
                var x = location.href;
                window.location.href = x + "?tab=T";
            }
        });
    });
}

function Restorefun(id, tab1data) {
    $("#AlertNo").attr('value', tab1data);
    $('#Approve .approveMsg').text("Are you sure you want to restore this record on the website? Click on Yes to confirm.");
    $('#Approve1').show();
    // $('#Approve').modal({
    //     backdrop: 'static',
    //     keyboard: false
    // });
    $("#Approve").modal("show");
    var RESTORE_URL = window.site_url + "/powerpanel/RestoreData_Listing";
    $(document).on('click', '#Approve1', function() {
        // $('#Approve').modal('hide');
        // $('body').loader(loaderConfig);
        $.ajax({
            type: 'POST',
            url: RESTORE_URL,
            data: 'id=' + id + "&tabdata=" + tab1data + '&moduleid=' + Quick_module_id,
            success: function(msg) {
                $("#Approve").modal("hide");
                // $(".close").trigger('click');
                // $.loader.close(true);
                var x = location.href;
                window.location.href = x + "?tab=P";
            }
        });
    });
}

function UnArchivefun(id, tab1data) {
    $("#AlertNo").attr('value', tab1data);
    $('#Approve .approveMsg').text("Are you sure you want to unarchive this record on the website? Click on Yes to confirm.");
    $('#Approve1').show();
    // $('#Approve').modal({
    //     backdrop: 'static',
    //     keyboard: false
    // });
    $("#Approve").modal("show");
    var UNARCHIVE_URL = window.site_url + "/powerpanel/UnArchiveData_Listing";
    $(document).on('click', '#Approve1', function() {
        $.ajax({
            type: 'POST',
            url: UNARCHIVE_URL,
            data: 'id=' + id + "&tabdata=" + tab1data + '&moduleid=' + Quick_module_id,
            success: function(msg) {
                $('#Approved .approveMsg').text("The record has been successfully unarchive on the website.");
                $('#Approved').show();
                // $('#Approved').modal({
                //     backdrop: 'static',
                //     keyboard: false
                // });
                $("#Approved").modal("show");
                $(document).on('click', '#ApprovedSuccess', function() {
                    $(".close").trigger('click');
                    var x = location.href;
                    window.location.href = x + "?tab=P";
                });
            }
        });
    });
}


function GetCopyPage(id) {
    $('#Approve .approveMsg').text("Are you sure you want to duplicate this record on the website? Click on Yes to confirm.");
    $('#Approve1').show();
    // $('#Approve').modal({
    //     backdrop: 'static',
    //     keyboard: false
    // });
    $("#Approve").modal("show");

    var Copy_URL = window.site_url + "/powerpanel/Copy_Listing";
    $(document).on('click', '#Approve1', function() {
        $.ajax({
            type: 'POST',
            url: Copy_URL,
            data: 'id=' + id + '&moduleid=' + Quick_module_id,
            success: function(msg) {
                $('#Approved .approveMsg').text("The record has been successfully duplicate on the website.");
                $('#Approved').show();
                // $('#Approved').modal({
                //     backdrop: 'static',
                //     keyboard: false
                // });
                $("#Approved").modal("show");
                $(document).on('click', '#ApprovedSuccess', function() {
                    var x = location.href;
                    window.location.href = x + "?tab=P";
                });
            }
        });
    });
}

$(document).on("switchChange.bootstrapSwitch", ".pub", function(event, state) {
    var $this = $(this);
    $("#AlertNo").attr('value', 'D');
    $('#Approve .approveMsg').text("Are you sure you want to Publish this record on the website? Click on Yes to confirm.");
    $('#Approve1').show();
    // $('#Approve').modal({
    //     backdrop: 'static',
    //     keyboard: false
    // });
    $("#Approve").modal("show");
    var DRAFT_URL = window.site_url + "/powerpanel/RemoveDarftData";

    $(document).on('click', '#Approve1', function() {
        var id = $this.data('alias');
        $.ajax({
            type: 'POST',
            url: DRAFT_URL,
            data: 'id=' + id + '&moduleid=' + Quick_module_id,
            success: function(msg) {
                $('#Approved .approveMsg').text("The record has been successfully Publish on the website.");
                $('#Approved').show();
                // $('#Approved').modal({
                //     backdrop: 'static',
                //     keyboard: false
                // });
                $("#Approved").modal("show");
                $(document).on('click', '#ApprovedSuccess', function() {
                    location.reload();
                });
            }
        });
    });
});
$(document).ready(function() {
    setInterval(function() {
        $('.addhiglight').closest("td").closest("tr").addClass('higlight');
    }, 800);
});
var Favorite_URL = window.site_url + "/powerpanel/Favorite_Listing";

function GetFavorite(id, flag, pid) {
    $.ajax({
        type: 'POST',
        url: Favorite_URL,
        data: 'id=' + id + '&flag=' + flag + '&tab=' + pid + '&moduleid=' + Quick_module_id,
        success: function(msg) {
            var x = location.href;
            if (flag == 'Y') {
                window.location.href = x + "?tab=F";
            } else if (flag == 'N') {
                window.location.href = x + "?tab=" + pid;
            }
        }
    });
}
var Archive_URL = window.site_url + "/powerpanel/Archive_Listing";

function Archivefun(id, flag, pid) {
    $.ajax({
        type: 'POST',
        url: Archive_URL,
        data: 'id=' + id + '&flag=' + flag + '&tab=' + pid + '&moduleid=' + Quick_module_id,
        success: function(msg) {
            var x = location.href;
            window.location.href = x + "?tab=R";
        }
    });
}


$(document).ready(function() {
    $("#noexpiry").on("click", function() {
        //    $('#noexpiry').click(function () {
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

});

var ValidateReply = function() {
    var handleReplyToFrm = function() {
        $("#QuickEditForm").validate({
            errorElement: 'span', //default input error message container
            errorClass: 'help-block error', // default input error message class
            ignore: [],
            rules: {
                name: {
                    required: true,
                    noSpace: true,
                    no_url: true
                },
                start_date_time: {
                    required: true,
                    noSpace: true,
                    no_url: true
                },
                end_date_time: {
                    daterange: true,
                    required: {
                        depends: function() {
                            var isChecked = $('#end_date_time').attr('data-exp');
                            if (isChecked == 0) {
                                return $('input[name=end_date_time]').val().length == 0;
                            }
                        }
                    },
                    noSpace: true,
                    no_url: true
                },
            },
            messages: {
                name: {
                    required: "Name field is required",
                },
                start_date_time: {
                    required: "Start date field is required.",
                },
                end_date_time: {
                    // required: Lang.get('validation.required', { attribute: Lang.get('template.enddate') }),
                    required: "End date field is required.",
                    daterange: 'The end date must be a greater than start date.'
                },
            },
            errorPlacement: function(error, element) {
                error.insertAfter(element);
            },
            invalidHandler: function(event, validator) { //display error alert on form submit   
                $('.alert-danger', $('#QuickEditForm')).show();
            },
            highlight: function(element) { // hightlight error inputs
                $(element).closest('.form-group').addClass('has-error'); // set error class to the control group
            },
            unhighlight: function(element) {
                $(element).closest('.form-group').removeClass('has-error'); // set error class to the control group
            },
            submitHandler: function(form) {
                $("#quick_submit").attr("disabled", "disabled");
                submitQuickedit();
                return false;
            }
        });
        $('#QuickEditForm input').keypress(function(e) {
            if (e.which == 13) {
                if ($('#QuickEditForm').validate().form()) {
                    $("#quick_submit").attr("disabled", "disabled");
                    submitQuickedit();
                }
                return false;
            }
        });
    }
    return {
        //main function to initiate the module
        init: function() {
            handleReplyToFrm();
        }
    };
}();
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

jQuery(document).ready(function() {
    ValidateReply.init();
});

function HitsPopup(id, aid, name, tab) {
    var ajaxurl = site_url + '/powerpanel/Hits_Listing';
    $("#wait").css("display", "block");
    $.ajax({
        url: ajaxurl,
        data: {
            id: aid,
            name: name
        },
        type: "POST",
        success: function(webdata) {
            $('#desc_' + id + "_" + tab).modal('show');
            $("#webdata_" + id + "_" + tab).html(webdata);
            $("#wait").css("display", "none");
        }
    });
}

$(document).on("click", ".tabclasssetting", function() {
    if ($(this).prop('checked') == true) {
        var column_disp = 'Y';
    } else {
        var column_disp = 'N';
    }
    var tabid = $(this).attr('data-tabid');
    var columnno = $(this).attr('data-columnno');
    var columnname = $(this).attr('data-columnname');
    var columnid = $(this).attr('id');
    var ajaxurl = site_url + '/powerpanel/HideColumn';
    $.ajax({
        url: ajaxurl,
        data: { moduleid: Quick_module_id, columnname: columnname, tabid: tabid, columnno: columnno, column_disp: column_disp, columnid: columnid },
        type: "POST",
        dataType: "HTML",
        success: function(data) {
            var x = location.href;
            window.location.href = x + "?tab=" + tabid;
        }
    });
});