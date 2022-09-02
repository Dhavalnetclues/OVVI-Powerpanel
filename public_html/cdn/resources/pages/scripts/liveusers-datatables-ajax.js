var grid = '';
var TableDatatablesAjax = function() {
    var initPickers = function() {
        //init date pickers
        $('.date-picker').datepicker({
            rtl: App.isRTL(),
            autoclose: true
        });
    }
    var handleRecords = function() {
        grid = new Datatable();
        var ip = '';
        var totalRec;
        grid.init({
            src: $("#datatable_ajax"),
            onSuccess: function(grid, response) {
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
            onError: function(grid) {
                // execute some code on network or other general error
            },
            onDataLoad: function(grid) {
                // execute some code on ajax data load
            },
            loadingMessage: 'Loading...',
            dataTable: { // here you can define a typical datatable settings from http://datatables.net/usage/options
                // Uncomment below line("dom" parameter) to fix the dropdown overflow issue in the datatable cells. The default datatable layout
                // setup uses scrollable div(table-scrollable) with overflow:auto to enable vertical scroll(see: assets/global/scripts/datatable.js).
                // So when dropdowns used the scrollable div should be removed.
                //"dom": "<'row'<'col-md-8 col-sm-12'pli><'col-md-4 col-sm-12'<'table-group-actions pull-right'>>r>t<'row'<'col-md-8 col-sm-12'pli><'col-md-4 col-sm-12'>>",
                "bStateSave": false, // save datatable state(pagination, sort, etc) in cookie.
                "lengthMenu": [
                    [10, 20, 50, 100],
                    [10, 20, 50, 100] // change per page values here
                ],
                "pageLength": 100, // default record count per page
                //Code for sorting
                "serverSide": true,
                "columns": [{
                        "data": 0,
                        className: 'td_checker mob-show_div',
                        "bSortable": false
                    },
                    {
                        "data": 1,
                        className: 'td_checker mob-show_div',
                        "bSortable": false
                    }, {
                        "data": 2,
                        className: 'text-left',
                        "name": 'varCountry_name mob-show_div',
                        "bSortable": true
                    }, {
                        "data": 3,
                        className: 'text-center mob-show_div',
                        "name": 'varIpAddress',
                        "bSortable": true
                    }, {
                        "data": 4,
                        className: 'text-center',
                        "bSortable": false
                    }, {
                        "data": 5,
                        className: 'text-center',
                        "name": 'updated_at',
                        "bSortable": true
                    }, {
                        "data": 6,
                        className: 'text-center mob-show_div',
                        "bSortable": false
                    }
                ],
                "ajax": {
                    "url": window.site_url + "/powerpanel/live-user/get-list", // ajax source
                },
                "order": [
                        [5, "desc"]
                    ] // set first column as a default sort by asc
            }
        });
        $(document).on('keyup', '#searchfilter', function(e) {
            e.preventDefault();
            var action = $('#searchfilter').val();
            if (action.length >= 2) {
                $.cookie('LiveUsersearch', action);
                grid.setAjaxParam("customActionType", "group_action");
                grid.setAjaxParam("searchValue", action);
                grid.setAjaxParam("id", grid.getSelectedRows());
                grid.getDataTable().ajax.reload();
            } else if (action.length < 1) {
                $.removeCookie('LiveUsersearch');
                grid.setAjaxParam("customActionType", "group_action");
                grid.setAjaxParam("searchValue", action);
                grid.setAjaxParam("id", grid.getSelectedRows());
                grid.getDataTable().ajax.reload();
            }
        });
        grid.setAjaxParam("customActionType", "group_action");
        grid.clearAjaxParams();
        $(document).on('change', '#country', function(e) {
            e.preventDefault();
            var action = $('#country').val();
            if (action != "") {
                grid.setAjaxParam("customActionType", "group_action");
                grid.setAjaxParam("CounValue", action);
                grid.setAjaxParam("id", grid.getSelectedRows());
                grid.getDataTable().ajax.reload();
            } else {
                grid.setAjaxParam("customActionType", "group_action");
                grid.setAjaxParam("CounValue", action);
                grid.setAjaxParam("id", grid.getSelectedRows());
            }
        });
        $('a[data-toggle="tab"][id="MenuItem1"]').on('shown.bs.tab', function(e) {
            $("#hidefilter").show();
            e.preventDefault();
            grid.setAjaxParam("Before15Day", 'N');
            grid.setAjaxParam("id", grid.getSelectedRows());
            grid.getDataTable().ajax.reload();
        });
        $('a[data-toggle="tab"][id="MenuItem2"]').on('shown.bs.tab', function(e) {
            $("#hidefilter").show();
            e.preventDefault();
            grid.setAjaxParam("Before15Day", 'Y');
            grid.setAjaxParam("id", grid.getSelectedRows());
            grid.getDataTable().ajax.reload();
        });
        $(document).on('click', '#liveUserRange', function(e) {
            e.preventDefault();
            var action = {};
            action['from'] = $('#start_date').val();
            action['to'] = $('#end_date').val();
            if (action['from'] != '' && action['to'] != '') {
                grid.setAjaxParam("customActionType", "group_action");
                grid.setAjaxParam("rangeFilter", action);
                grid.setAjaxParam("id", grid.getSelectedRows());
                grid.getDataTable().ajax.reload();
            } else if (action['from'] != '' && action['to'] == '') {
                grid.setAjaxParam("customActionType", "group_action");
                grid.setAjaxParam("rangeFilter", action);
                grid.setAjaxParam("id", grid.getSelectedRows());
                grid.getDataTable().ajax.reload();
            } else {
                grid.setAjaxParam("customActionType", "group_action");
                grid.setAjaxParam("rangeFilter", action);
                grid.setAjaxParam("id", grid.getSelectedRows());
            }
        });

        $(document).on('click', '#refresh', function(e) {
            $('#start_date').val('');
            $('#end_date').val('');
            grid.setAjaxParam("rangeFilter", '');
            $('#country').val('');
            grid.getDataTable().ajax.reload();
        });

        $('#ExportRecord').on('click', function(e) {
            country = $('#country').val();
            startDate = $('#start_date').val();
            endDate = $('#end_date').val();
            e.preventDefault();
            if (totalRec < 1) {
                $('#noRecords').modal('show');
            } else {
                $('#noRecords').modal('hide');
                var exportRadioVal = $("input[name='export_type']:checked").val();
                if (exportRadioVal != '') {
                    if (exportRadioVal == 'selected_records') {
                        if ($('#ExportRecord').click) {
                            if ($('input[name="block[]"]:checked').val()) {
                                ip = '?' + $('input[name="block[]"]:checked').serialize() + '&' + 'export_type' + '=' + exportRadioVal;
                                if (country != '') {
                                    ip = ip + '&country=' + country;
                                }
                                if (startDate != '') {
                                    ip = ip + '&startDate=' + startDate;
                                }
                                if (endDate != '') {
                                    ip = ip + '&endDate=' + endDate;
                                }
                                var ajaxurl = window.site_url + "/powerpanel/live-user/ExportRecord" + ip;
                                window.location = ajaxurl;
                                grid.getDataTable().ajax.reload();
                            } else {
                                $('#noSelectedRecords').modal('show');
                            }
                        }
                    } else {
                        data = '';
                        if (country != '') {
                            data = '?country=' + country;
                            if (startDate != '') {
                                data = data + '&startDate=' + startDate;
                            }
                            if (endDate != '') {
                                data = data + '&endDate=' + endDate;
                            }
                        } else {
                            if (startDate != '' && endDate != '') {
                                data = '?startDate=' + startDate + '&endDate=' + endDate;
                            } else if (startDate != '') {
                                data = '?startDate=' + startDate;
                            } else if (endDate != '') {
                                data = data + '?endDate=' + endDate;
                            }
                        }
                        $('#selected_records').modal('hide');
                        var ajaxurl = window.site_url + "/powerpanel/live-user/ExportRecord" + data;
                        window.location = ajaxurl;
                    }
                }
            }
        });
    }
    return {
        //main function to initiate the module
        init: function() {
            initPickers();
            handleRecords();
        }
    };
}();
jQuery(document).ready(function() {
    $.ajaxSetup({
        headers: {
            'X-CSRF-Token': $('input[name="_token"]').val()
        }
    });
    TableDatatablesAjax.init();
});

var BLOCK_URL = site_url + ' /powerpanel/live-user/block_user ';

function Block(id) {
    $('#Approve .approveMsg').text("Are you sure you want to block this user on the website? Click on Yes to confirm.");
    $('#Approve1').show();
    $('#Approve').modal({
        backdrop: 'static',
        keyboard: false
    });
    $(document).on('click', '#Approve1', function() {
        $('#Approve').hide();
        $('body').loader(loaderConfig);
        $.ajax({
            type: 'POST',
            url: BLOCK_URL,
            data: 'id=' + id,
            success: function(msg) {
                $(".close").trigger('click');
                $.loader.close(true);
                grid.getDataTable().ajax.reload();
            }
        });
    });
}

var UN_BLOCK_URL = site_url + ' /powerpanel/live-user/un_block_user ';

function UnBlock(id) {
    $('#Approve .approveMsg').text("Are you sure you want to un-block this user on the website? Click on Yes to confirm.");
    $('#Approve1').show();
    $('#Approve').modal({
        backdrop: 'static',
        keyboard: false
    });
    $(document).on('click', '#Approve1', function() {
        $('#Approve').hide();
        $('body').loader(loaderConfig);
        $.ajax({
            type: 'POST',
            url: UN_BLOCK_URL,
            data: 'id=' + id,
            success: function(msg) {
                $(".close").trigger('click');
                $.loader.close(true);
                grid.getDataTable().ajax.reload();
            }
        });
    });
}