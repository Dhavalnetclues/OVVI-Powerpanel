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
    if(searchid != ''){
        var id = '?id='+searchid;
    }else{
        var id = '';
    }
    var handleRecords = function () {
        grid = new Datatable();
        var ip = '';
        var totalRec;
        grid.init({
            src: $("#datatable_ajax"),
            onSuccess: function (grid, response) {
                if (response.recordsTotal < 1) {
                    if (response.recordsTotal < 1) {
                        $('.deleteMass').hide();
                    } else {
                        $('.deleteMass').show();
                    }
                    $('.ExportRecord').hide();
                    $("#menu1.tab-pane .notabreocrd").show();
										$("#menu1.tab-pane .withrecords").hide();
                } else {
                    $('.ExportRecord').show();
                    $("#menu1.tab-pane .notabreocrd").hide();
										$("#menu1.tab-pane .withrecords").show();
                }
                // if(response.recordsTotal < 20) {
                //     $('.gridjs-pages').hide();
                // } else {
                //     $('.gridjs-pages').show();
                // }
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
                    })
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
                "pageLength": 10, // default record count per page
                drawCallback:function(){
                    // var $api = this.api();
                    // var pages = $api.page.info().pages;
                    // var rows = $api.data().length;
                    // if(pages<=1){
                    //     $('.dataTables_info').css('display','none');
                    //     $('.dataTables_paginate').css('display','none');
                    // }
                },
                // Code for sorting
                "serverSide": true,
                "lengthChange": false,
                "pagingType": "simple_numbers",
                "language": {
                    "info": '<div role="status" aria-live="polite" class="gridjs-summary">Showing <b>_START_</b> to <b>_END_</b> of <b>_TOTAL_</b> results</div>', // title="Page 1 of 2"
                },
                "columns": [
                    {"data": 0, "class": 'td_checker', "bSortable": false},
                    {"data": 1, "class": 'text-left', "name": 'varName', "bSortable": true},
                    {"data": 2, "class": 'text-left', "name": 'varEmail', "bSortable": false},
                    {"data": 3, "class": 'text-center', "bSortable": false},
                    {"data": 4, "class": 'text-center', "bSortable": false},
                    {"data": 5, "class": 'text-center', "bSortable": false},
                    {"data": 6, "class": 'text-center', "bSortable": false},
                    {"data": 7, "class": 'text-center', "name": 'varIpAddress', "bSortable": false},
                    {"data": 8, "class": 'text-center', "name": 'created_at', "bSortable": true},
                ],
                "ajax": {
                    "url": window.site_url + "/powerpanel/reseller-leads/get_list"+id, // ajax source
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
                $.cookie('feedbackleadsearch', action);
                grid.setAjaxParam("customActionType", "group_action");
                grid.setAjaxParam("searchValue", action);
                grid.setAjaxParam("id", grid.getSelectedRows());
                grid.getDataTable().ajax.reload();
            } else if (action.length < 1) {
                $.removeCookie('feedbackleadsearch');
                grid.setAjaxParam("customActionType", "group_action");
                grid.setAjaxParam("searchValue", action);
                grid.setAjaxParam("id", grid.getSelectedRows());
                grid.getDataTable().ajax.reload();
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
                            if ($('input[name="delete[]"]:checked').val()) {
                                ip = '?' + $('input[name="delete[]"]:checked').serialize() + '&' + 'export_type' + '=' + exportRadioVal;
                                var ajaxurl = window.site_url + "/powerpanel/reseller-leads/ExportRecord" + ip;
                                window.location = ajaxurl;
                                grid.getDataTable().ajax.reload();
                            } else {
                                $('#noSelectedRecords').modal('show');
                            }
                        }
                    } else {
                        $('#selected_records').modal('hide');
                        var ajaxurl = window.site_url + "/powerpanel/reseller-leads/ExportRecord";
                        window.location = ajaxurl;
                    }
                }
            }
        });

        $(document).on('change', '#getdemoRange', function(e) {
            e.preventDefault();
            var action = {};
            action['from'] = $('#start_date').val();
            action['to'] = $('#end_date').val();
            if (action['from'] != '' && action['to'] != '') {
                grid.setAjaxParam("customActionType", "group_action");
                grid.setAjaxParam("rangeFilter", action);
                grid.setAjaxParam("id", grid.getSelectedRows());
                grid.getDataTable().ajax.reload();
            } else if(action['from'] != '' && action['to'] == ''){
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
$(window).on('load', function () {
    if ($.cookie('feedbackleadsearch')) {
        $('#searchfilter').val($.cookie('feedbackleadsearch'));
        $('#searchfilter').trigger('keyup');
    }
});
jQuery(document).ready(function () {
    $.ajaxSetup({
        headers: {
            'X-CSRF-Token': $('input[name="_token"]').val()
        }
    });
    TableDatatablesAjax.init();
});