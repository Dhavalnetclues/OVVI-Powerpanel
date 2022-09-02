var grid = '';
var grid1 = '';
var TableDatatablesAjax = function () {
    var initPickers = function () {
        //init date pickers
        $('.date-picker').datepicker({
            rtl: App.isRTL(),
            autoclose: true
        });
    }
    var handleRecords = function () {
        grid = new Datatable();
        var ip = '';
        var totalRec;
        grid.init({
            src: $("#datatable_ajax"),
            onSuccess: function (grid, response) {
                if (response.recordsTotal < 1) {
                    $('.deleteMass').hide();
                } else {
                    $('.deleteMass').show();
                }
                if (response.recordsTotal < 1) {
                    $('.ExportRecord').hide();
                } else {
                    $('.ExportRecord').show();
                }

                // grid:        grid object
                // response:    json object of server side ajax response
                // execute some code after table records loaded		
                // get all typeable inputs		
                totalRec = response.recordsTotal;
            },
            onError: function (grid) {
                // execute some code on network or other general error
            },
            onDataLoad: function (grid) {
                // execute some code on ajax data load
            },
            loadingMessage: 'Loading...',
            dataTable: {// here you can define a typical datatable settings from http://datatables.net/usage/options
                // Uncomment below line("dom" parameter) to fix the dropdown overflow issue in the datatable cells. The default datatable layout
                // setup uses scrollable div(table-scrollable) with overflow:auto to enable vertical scroll(see: assets/global/scripts/datatable.js).
                // So when dropdowns used the scrollable div should be removed.
                //"dom": "<'row'<'col-md-8 col-sm-12'pli><'col-md-4 col-sm-12'<'table-group-actions pull-right'>>r>t<'row'<'col-md-8 col-sm-12'pli><'col-md-4 col-sm-12'>>",
                "deferRender": true,
                "stateSave": true, // save datatable state(pagination, sort, etc) in cookie.
                "lengthMenu": [
                    [10, 20, 50, 100],
                    [10, 20, 50, 100] // change per page values here
                ],
                "pageLength": 100, // default record count per page
                //Code for sorting
                "serverSide": true,
                "columns": [
                    {"data": 0, className: 'td_checker mob-show_div', "bSortable": false},
                    {"data": 1, className: 'text-left mob-show_div', "name": 'varTitle'},
                    {"data": 2, className: 'text-left mob-show_div', "name": 'intType'},
                    {"data": 3, className: 'text-center', "name": 'varImage'},
                    {"data": 4, className: 'text-center', "name": 'varCaptcher'},
                   
                    {"data": 5, className: 'text-center', "name": 'txtShortDescription'},
                    {"data": 6, className: 'text-center', "name": 'varLink'},
                     {"data": 7, className: 'text-center', "name": 'chrStatus'},
                    {"data": 8, className: 'text-center mob-show_div', "name": 'created_at'},
                ],
                "ajax": {
                    "url": window.site_url + "/powerpanel/submit-tickets/get_list", // ajax source
                },
                "order": [
                    [8, "desc"]
                ]// set first column as a default sort by asc
            }
        });
        $(document).on('keyup', '#searchfilter', function (e) {
            e.preventDefault();
            var action = $('#searchfilter').val();
            if (action.length >= 2) {
                $.cookie('ContactLeadsearch', action);
                grid.setAjaxParam("customActionType", "group_action");
                grid.setAjaxParam("searchValue", action);
                grid.setAjaxParam("id", grid.getSelectedRows());
                grid.getDataTable().ajax.reload();
            } else if (action.length < 1) {
                $.removeCookie('ContactLeadsearch');
                grid.setAjaxParam("customActionType", "group_action");
                grid.setAjaxParam("searchValue", action);
                grid.setAjaxParam("id", grid.getSelectedRows());
                grid.getDataTable().ajax.reload();
            }
        });

        $(document).on('change', '.change_ticket_status', function (e) {
            e.preventDefault();
            var $this = $(this);
            var recordId = $this.find(':selected').data('rid');
            var ticketUserId = $this.find(':selected').data('uid');  
            var ticketStatus = $this.find(':selected').val();
            var useremailid = $("#useremailid").val();
            var username = $("#username").val();
            
            var notificationText = '';
            if (ticketStatus == 'P') {
                 var notificationText = "Ticket status has been change to Pending";
            } else if (ticketStatus == 'H') {
                var notificationText = "Ticket status has been change to On Hold";
            } else if (ticketStatus == 'G') {
                var notificationText = "Ticket status has been change to On Going";
            } else if (ticketStatus == 'C') {
               var notificationText = "Ticket status has been change to Completed";
            }else if (ticketStatus == 'N') {
               var notificationText = "Ticket status has been change to New Implementation";
            }
             $('#reply_to_subject').val(notificationText);
             $('#reply_to_email').val(useremailid);
             $('#reply_lead_Id').val(recordId);
             $('#reply_lead_name').val(username);
             $('#ticketStatus').val(ticketStatus);
            $('#leadReplyModel').modal('show');
        });

        $('#ExportRecord').on('click', function (e) {
            e.preventDefault();
            if (totalRec < 1) {
                $('#noRecords').modal('show');
            } else {
                $('#noRecords').modal('hide');
                var exportRadioVal = $("input[name='export_type']:checked").val();
                if (exportRadioVal != '') {
                    if (exportRadioVal == 'selected_records') {
                        if ($('#ExportRecord').click) {
                            if ($('input[name="delete[]"]:checked').val()) {
                                ip = '?' + $('input[name="delete[]"]:checked').serialize() + '&' + 'export_type' + '=' + exportRadioVal;
                                var ajaxurl = window.site_url + "/powerpanel/submit-tickets/ExportRecord" + ip;
                                window.location = ajaxurl;
                                grid.getDataTable().ajax.reload();
                            } else {
                                $('#noSelectedRecords').modal('show');
                            }
                        }
                    } else {
                        $('#selected_records').modal('hide');
                        var ajaxurl = window.site_url + "/powerpanel/submit-tickets/ExportRecord";
                        window.location = ajaxurl;
                    }
                }
            }
        });
        grid.setAjaxParam("customActionType", "group_action");
        grid.clearAjaxParams();
        grid.getDataTable().columns().iterator('column', function (ctx, idx) {
            $(grid.getDataTable().column(idx).header()).append('<span class="sort-icon"/>');
        });
    }
    return {
        //main function to initiate the module
        init: function () {
            initPickers();
            handleRecords();
        }
    };
}();

var ValidateReply = function () {
    var handleReplyToFrm = function () {
        $("#leadReplyForm").validate({
            errorElement: 'span', //default input error message container
            errorClass: 'help-block error', // default input error message class
            ignore: [],
            rules: {
                reply_to_email: {
                    required: true,
                    noSpace: true,
                    no_url: true
                },
                reply_to_subject: {
                    required: true,
                    noSpace: true,
                    no_url: true
                },
                reply_to_message: {
                    required: true,
                    no_url: true
                },
            },
            messages: {
                reply_to_email: {
                    required: "Email is required",
                },
                reply_to_subject: {
                    required: "Subject is required",
                },
                reply_to_message: {
                    required: "Message is required"
                }
            },
            errorPlacement: function (error, element) {
                if (element.attr('id') == 'g-recaptcha-response-1') {
                    error.insertAfter(element.parent());
                } else {
                    error.insertAfter(element);
                }
            },
            invalidHandler: function (event, validator) { //display error alert on form submit   
                $('.alert-danger', $('#leadReplyForm')).show();
            },
            highlight: function (element) { // hightlight error inputs
                $(element).closest('.form-group').addClass('has-error'); // set error class to the control group
            },
            unhighlight: function (element) {
                $(element).closest('.form-group').removeClass('has-error'); // set error class to the control group
            },
            submitHandler: function (form) {
                $("#lead_submit").attr("disabled", "disabled");
                //SetBackGround();
                leadsubmit();
                //form.submit();
                return false;
            }
        });
        $('#leadReplyForm input').keypress(function (e) {
            if (e.which == 13) {
                if ($('#leadReplyForm').validate().form()) {
                    $("#lead_submit").attr("disabled", "disabled");
                    //SetBackGround();
                    //$('#leadReplyForm').submit(); //form validation success, call ajax form submit
                    leadsubmit();
                }
                return false;
            }
        });
    }
    return {
        //main function to initiate the module
        init: function () {
            handleReplyToFrm();
        }
    };
}();

$(window).on('load', function () {
    if ($.cookie('ContactLeadsearch')) {
        $('#searchfilter').val($.cookie('ContactLeadsearch'));
        $('#searchfilter').trigger('keyup');
    }
});
jQuery(document).ready(function () {
    $.ajaxSetup({
        headers: {
            'X-CSRF-Token': $('input[name="_token"]').val()
        }
    });
     ValidateReply.init();
    TableDatatablesAjax.init();
});

jQuery.validator.addMethod("emailFormat", function (value, element) {
    // allow any non-whitespace characters as the host part
    return this.optional(element) || /^[_A-Za-z0-9-]+(\.[_A-Za-z0-9-]+)*@[A-Za-z0-9-]+(\.[A-Za-z0-9-]+)*(\.[A-Za-z]{2,4})$/.test(value);
}, 'Enter valid email format');
jQuery.validator.addMethod("messageValidation", function (value, element) {
    // allow any non-whitespace characters as the host part
    return this.optional(element) || /<(\w+)((?:\s+\w+(?:\s*=\s*(?:(?:"[^"]*")|(?:'[^']*')|[^>\s]+))?)*)\s*(\/?)>/.test(value) == false ? true : false;
}, 'Enter valid message');
jQuery.validator.addMethod("xssProtection", function (value, element) {
    // allow any non-whitespace characters as the host part
    return this.optional(element) || /<(\w+)((?:\s+\w+(?:\s*=\s*(?:(?:"[^"]*")|(?:'[^']*')|[^>\s]+))?)*)\s*(\/?)>/.test(value) == false ? true : false;
}, 'Enter valid input');
$.validator.addMethod("check_special_char", function (value, element) {
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
$.validator.addMethod('no_url', function (value, element) {
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



function leadsubmit() {
    if ($("#leadReplyForm").valid()) {
        var frmData = $('#leadReplyForm').serialize();
        jQuery.ajax({
            type: "POST",
            url: Email_reply_URL,
            data: frmData,
            dataType: 'json',
            async: true,
            success: function (data) {
                if (data.success == 1) {
                    $("#leadReplyForm").find(".success").text(data.msg);
                    $("#leadReplyForm").find(".success").show();
                    $("#leadReplyForm").find("label.error").hide();
                    $('#leadReplyModel').modal('hide');
                    grid.getDataTable().ajax.reload();
                } else {
                    $("#leadReplyForm").find("label.error").text(data.msg);
                    $("#leadReplyForm").find("label.error").show();
                    $("#leadReplyForm").find(".success").hide();
                }
            }
        });
    }
}