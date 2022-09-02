var TableDatatablesAjax = function () {
    var initPickers = function () {
        //init date pickers
        $('.date-picker').datepicker({
            rtl: App.isRTL(),
            autoclose: true
        });
    }
    var handleRecords = function () {
        var grid = new Datatable();
        var ip = '';
        var totalRec;
        grid.init({
            src: $("#datatable_ajax"),
            onSuccess: function (grid, response) {
                if (response.recordsTotal < 1) {
                    $('.deleteMass').hide();
                    $('.ExportRecord').hide();
                } else {
                    $('.deleteMass').show();
                    $('.ExportRecord').show();
                }
                // grid:        grid object
                // response:    json object of server side ajax response
                // execute some code after table records loaded
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
                "columns": [{
                        "data": 0,
                        className: 'text-center  mob-show_div',
                        "bSortable": false
                    },
                    {
                        "data": 1,
                        className: 'text-center mob-show_div',
                        "bSortable": false
                    }, {
                        "data": 2,
                        className: 'text-left mob-show_div',
                        "bSortable": false
                    },
                   
                    {
                        "data": 3,
                        className: 'text-left  mob-show_div',
                        "bSortable": false
                    }, {
                        "data": 4,
                        className: 'text-left mob-show_div',
                        "name": 'varAction'
                    }, {
                        "data": 5,
                        className: 'text-center  mob-show_div',
                        "name": 'varIpAddress'
                    }, {
                        "data": 6,
                        className: 'text-center  mob-show_div',
                        "name": 'created_at'
                    }],
                "ajax": {
                    "url": window.site_url + "/powerpanel/log/get_list" + rid + mid + "", // ajax source
                },
                "order": [
                    [6, "desc"]
                ],
            }
        });
        //This code for search filter
        $(document).on('keyup', '#searchfilter', function (e) {
            e.preventDefault();
            var action = $('#searchfilter').val();
            if (action.length >= 2) {
                //$.cookie('LogMangersearch',action);              
                grid.setAjaxParam("customActionType", "group_action");
                grid.setAjaxParam("searchValue", action);
                grid.setAjaxParam("id", grid.getSelectedRows());
                grid.getDataTable().ajax.reload();
            } else if (action.length < 1) {
                //$.removeCookie('LogMangersearch');
                grid.setAjaxParam("customActionType", "group_action");
                grid.setAjaxParam("searchValue", action);
                grid.setAjaxParam("id", grid.getSelectedRows());
                grid.getDataTable().ajax.reload();
            }
        });
        $(document).on('change', '#modulefilter', function (e) {
            e.preventDefault();
            var action = $('#modulefilter').val();
            if (action != "") {
                grid.setAjaxParam("customActionType", "group_action");
                grid.setAjaxParam("customActionName", action);
                grid.setAjaxParam("customPageName", '');
                grid.setAjaxParam("id", grid.getSelectedRows());
                grid.getDataTable().ajax.reload();
            } else {
                grid.setAjaxParam("customActionType", "group_action");
                grid.setAjaxParam("customActionName", action);
                grid.setAjaxParam("id", grid.getSelectedRows());
            }
        });

        $(document).on('click', '.list_head_filter', function (e) {
            e.preventDefault();
            var action = $(this).attr("data-filterIdentity");
            if (action != "") {
                grid.setAjaxParam("customActionType", "group_action");
                grid.setAjaxParam("customFilterIdentity", action);
                grid.setAjaxParam("id", grid.getSelectedRows());
                grid.getDataTable().ajax.reload();
            } else {
                grid.setAjaxParam("customActionType", "group_action");
                grid.setAjaxParam("customFilterIdentity", "");
                grid.setAjaxParam("id", grid.getSelectedRows());
            }
        });
        $(document).on('change', '.list_head_filter', function (e) {
        	grid.setAjaxParam("customFilterIdentity","");
        });
        $(document).on('change', '#foritem', function (e) {
            e.preventDefault();
            var action = $('#foritem').val();
            if (action != "") {
                grid.setAjaxParam("customActionType", "group_action");
                grid.setAjaxParam("customPageName", action);
                grid.setAjaxParam("id", grid.getSelectedRows());
                grid.getDataTable().ajax.reload();
            } else {
                grid.setAjaxParam("customActionType", "group_action");
                grid.setAjaxParam("customPageName", action);
                grid.setAjaxParam("id", grid.getSelectedRows());
            }
        });

        $(document).on('change', '#userfilter', function (e) {
            e.preventDefault();
            var action = $('#userfilter').val();
            if (action != "") {
                grid.setAjaxParam("customActionType", "group_action");
                grid.setAjaxParam("customFilterUserId", action);
                grid.setAjaxParam("id", grid.getSelectedRows());
                grid.getDataTable().ajax.reload();
            } else {
                grid.setAjaxParam("customActionType", "group_action");
                grid.setAjaxParam("customFilterUserId", action);
                grid.setAjaxParam("id", grid.getSelectedRows());
            }
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
                            var matches = [];
                            $(".chkDelete:checked").each(function () {
                                matches.push(this.value);
                            });
                            if (matches.length > 0) {
                                matches = matches.toString();
                                //ip = '?delete=' + matches + '&' + 'export_type' + '=' + exportRadioVal;
                                QuerySringParams.delete = matches;
                                QuerySringParams.export_type = exportRadioVal;
                                var queryString = "?" + $.param(QuerySringParams);
                                var ajaxurl = window.site_url + "/powerpanel/log/ExportRecord" + queryString;
                                window.location = ajaxurl;
                                grid.getDataTable().ajax.reload();
                            } else {
                                $('#noSelectedRecords').modal('show');
                            }
                        }
                    } else {
                        $('#selected_records').modal('hide');
                        if (Object.keys(QuerySringParams).length > 0) {
                            var queryString = "?" + $.param(QuerySringParams);
                        } else {
                            var queryString = "";
                        }
                        var ajaxurl = window.site_url + "/powerpanel/log/ExportRecord" + queryString;
                        window.location = ajaxurl;
                    }
                }
            }
        });

        // handle group actionsubmit button click
        grid.getTableWrapper().on('click', '.table-group-action-submit', function (e) {
            e.preventDefault();
            var action = $(".table-group-action-input", grid.getTableWrapper());
            if (action.val() != "" && grid.getSelectedRowsCount() > 0) {
                grid.setAjaxParam("customActionType", "group_action");
                grid.setAjaxParam("customActionName", action.val());
                grid.setAjaxParam("id", grid.getSelectedRows());
                grid.getDataTable().ajax.reload();
                grid.clearAjaxParams();
            } else if (action.val() == "") {
                App.alert({
                    type: 'danger',
                    icon: 'warning',
                    message: 'Please select an action',
                    container: grid.getTableWrapper(),
                    place: 'prepend'
                });
            } else if (grid.getSelectedRowsCount() === 0) {
                App.alert({
                    type: 'danger',
                    icon: 'warning',
                    message: 'No record selected',
                    container: grid.getTableWrapper(),
                    place: 'prepend'
                });
            }
        });
        grid.setAjaxParam("customActionType", "group_action");
        grid.clearAjaxParams();
        grid.getDataTable().columns().iterator('column', function (ctx, idx) {
            $(grid.getDataTable().column(idx).header()).append('<span class="sort-icon"/>');
        });

        if (!showChecker) {
            grid.getDataTable().column(0).visible(false);
        } else {
            grid.getDataTable().column(0).visible(true);
        }
    }
    return {
        //main function to initiate the module
        init: function () {
            initPickers();
            handleRecords();
        }
    };
}();

jQuery(document).ready(function () {
    $.ajaxSetup({
        headers: {
            'X-CSRF-Token': $('input[name="_token"]').val()
        }
    });
    TableDatatablesAjax.init();


});
$('#modulefilter').on("change", function (e) {
    fn1($("#modulefilter option:selected").data('module'), $("#modulefilter option:selected").data('model'),$("#modulefilter option:selected").data('id'));
});

function fn1(moduleName, modelName, moduleid) {
    var ajaxUrl = site_url + '/powerpanel/log/selectRecords';
    jQuery.ajax({
        type: "POST",
        url: ajaxUrl,
        dataType: 'HTML',
        data: {"module": moduleName, "model": modelName,"id": moduleid, 'selected': selectedRecord},
        async: false,
        success: function (result) {
            $('#foritem').html(result).select2({
                placeholder: "Select Page",
//                width: '100%',
//                minimumResultsForSearch: 5
            });
        }
    });
}
