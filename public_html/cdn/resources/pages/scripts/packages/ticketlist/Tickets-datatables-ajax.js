var grid = '';
var grid1 = '';
var statusActions = [];
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
                    $("#menu1.tab-pane .notabreocrd").show();
										$("#menu1.tab-pane .withrecords").hide();
                } else {
                    $('.deleteMass').show();
                    $("#menu1.tab-pane .notabreocrd").hide();
										$("#menu1.tab-pane .withrecords").show();
                }
                if(response.recordsTotal < 20) {
                    $('.gridjs-pages').hide();
                } else {
                    $('.gridjs-pages').show();
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
                $(document).ready(function () {
                    var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
                    var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
                        return new bootstrap.Tooltip(tooltipTriggerEl)
                    });

                    $(".change_ticket_status").each(function() {
                        let selectOptions=[];
                        $(this.options).map(function(i,v) {
                        		let dataset = {
                        			uid:$(v).data('uid'),
                        			rid:$(v).data('rid')
                        		};
                            selectOptions.push({
                                value: this.value,
                                label: this.label,
                                selected: this.selected,
                                customProperties: this.dataset
                            });
                        });

                        this.options.length=0;
                        const choicesProp = new Choices(
                            this, {
                                choices : selectOptions
                            }
                        );
                        statusActions[$(this).attr('id')] = choicesProp;
                    });
                });
                // execute some code on ajax data load
            },
            loadingMessage: 'Loading...',
            dataTable: {// here you can define a typical datatable settings from http://datatables.net/usage/options
                // Uncomment below line("dom" parameter) to fix the dropdown overflow issue in the datatable cells. The default datatable layout
                // setup uses scrollable div(table-scrollable) with overflow:auto to enable vertical scroll(see: assets/global/scripts/datatable.js).
                // So when dropdowns used the scrollable div should be removed.
                "dom": "t <'gridjs-footer' <'gridjs-pagination'i <'gridjs-pages'p>>>",
                "deferRender": true,
                // "stateSave": true, // save datatable state(pagination, sort, etc) in cookie.
                // "lengthMenu": [
                //     [10, 20, 50, 100],
                //     [10, 20, 50, 100] // change per page values here
                // ],
                "pageLength": 20, // default record count per page
                drawCallback:function(){
                    var $api = this.api();
                    var pages = $api.page.info().pages;
                    var rows = $api.data().length;
                    if(pages<=1){
                        $('.dataTables_info').css('display','none');
                        $('.dataTables_paginate').css('display','none');
                    }
                },
                // Code for sorting
                "serverSide": true,
                "lengthChange": false,
                "pagingType": "simple_numbers",
                "language": {
                    "info": '<div role="status" aria-live="polite" class="gridjs-summary">Showing <b>_START_</b> to <b>_END_</b> of <b>_TOTAL_</b> results</div>', // title="Page 1 of 2"
                },
                "columns": [
                    {"data": 0, "class": 'text-center td_checker mob-show_div', "bSortable": false},
                    {"data": 1, "class": 'text-left mob-show_div', "name": 'varTitle', "bSortable": true},
                    {"data": 2, "class": 'text-left mob-show_div', "name": 'intType', "bSortable": false},
                    {"data": 3, "class": 'text-center', "name": 'varImage', "bSortable": false},
                    {"data": 4, "class": 'text-center', "name": 'varCaptcher', "bSortable": false},
                    {"data": 5, "class": 'text-center', "name": 'txtShortDescription', "bSortable": false},
                    {"data": 6, "class": 'text-center', "name": 'varLink', "bSortable": false},
                    {"data": 7, "class": 'text-left', "name": 'chrStatus', "bSortable": false},
                    {"data": 8, "class": 'text-center mob-show_div', "name": 'created_at'},],
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

            let ddVal = statusActions[$this.attr('id')].getValue();

            var recordId = ddVal.customProperties.rid;
            var ticketUserId = ddVal.customProperties.uid;
            var ticketStatus = ddVal.value;
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
                                $('#selectedRecords').modal('hide');
                                $('#noSelectedRecords').modal('show');
                            }
                        }
                    } else {
                        $('#selectedRecords').modal('hide');
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
        	$.fn.DataTable.ext.pager.numbers_length = 4;
            initPickers();
            handleRecords();
        }
    };
}();

var ValidateTicketReply = function () {
    var handleTicketReplyToFrm = function () {
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
            handleTicketReplyToFrm();
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
     ValidateTicketReply.init();
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