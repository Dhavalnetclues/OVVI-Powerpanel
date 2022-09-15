var TableDatatablesAjax = function() {
    var initPickers = function() {
        //init date pickers

    }
    var handleRecords = function() {
        var grid = new Datatable();
        var ip = '';
        var totalRec;
        grid.init({
            src: $("#datatable_ajax"),
            onSuccess: function(grid, response) {
                // if (response.recordsTotal < 1) { 
                // 	$('.deleteMass').hide();
                // 	$("#menu1.tab-pane .notabreocrd").show();
				// 	$("#menu1.tab-pane .withrecords").hide(); 
                // } else { 
                // 	$('.deleteMass').show(); 
                // 	$("#menu1.tab-pane .notabreocrd").hide();
				// 	$("#menu1.tab-pane .withrecords").show();
                // }
                // if(response.recordsTotal < 20) {
                //     $('.gridjs-pages').hide();
                // } else {
                //     $('.gridjs-pages').show();
                // }
                if (response.recordsTotal < 1) { $('.deleteMass').hide(); } else { $('.deleteMass').show(); }
                if (response.recordsTotal < 1) { $('.ExportRecord').hide(); } else { $('.ExportRecord').show(); }
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
                "dom": "t <'gridjs-footer' <'gridjs-pagination'i <'gridjs-pages'p>>>",
                "deferRender": true,
                // "scrollY":300,
                // "scrollX":true,
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
                    { "data": 0, "class": 'text-center td_checker', "bSortable": false },
                    { "data": 1, "class": 'text-left', "name": 'varTitle' },
                    { "data": 2, "class": 'text-left', "bSortable": false },
                    { "data": 3, "class": 'text-left', "bSortable": false },
                    // { "data": 4, "class": 'text-center', "bSortable": false },
                    { "data": 4, "class": 'text-center', "bSortable": false },
                    { "data": 5, "class": 'text-center', "bSortable": false },
                    { "data": 6, "class": 'text-center', "name": 'varIpAddress', "bSortable": false },
                    { "data": 7, "class": 'text-center', "name": 'dtCreateDate' },
                ],
                "ajax": {
                    "url": window.site_url + "/powerpanel/contact-lead/get_list", // ajax source
                },
                "order": [
                    [0, "desc"]
                ]
            }
        });

        $(document).on('keyup', '#searchfilter', function(e) {
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

        $(document).on('change', '#contactleadrange', function(e) {
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
            grid.getDataTable().ajax.reload();
        });

        $('#ExportRecord').on('click', function(e) {
            e.preventDefault();
            if (totalRec < 1) {
                $('#noRecords').modal('show');
            } else {
                $('#noRecords').modal('hide');
                var exportRadioVal = $("input[name='export_type']:checked").val();
                if (exportRadioVal != '') {
                    if (exportRadioVal == 'selected_records') {
                        if ($('#ExportRecord').click) {
                        	console.log($('input[name="delete[]"]:checked').val());
                            if ($('input[name="delete[]"]:checked').val()) {
                                ip = '?' + $('input[name="delete[]"]:checked').serialize() + '&' + 'export_type' + '=' + exportRadioVal;
                                var ajaxurl = window.site_url + "/powerpanel/contact-lead/ExportRecord" + ip;
                                window.location = ajaxurl;
                                grid.getDataTable().ajax.reload();
                            } else {
                                $('#noSelectedRecords').modal('show');
                            }
                        }
                    } else {
                        $('#selected_records').modal('hide');
                        var ajaxurl = window.site_url + "/powerpanel/contact-lead/ExportRecord";
                        window.location = ajaxurl;
                    }
                }
            }
        });

        grid.setAjaxParam("customActionType", "group_action");
        grid.clearAjaxParams();
        grid.getDataTable().columns().iterator('column', function(ctx, idx) {
            $(grid.getDataTable().column(idx).header()).append('<span class="sort-icon"/>');
        });
    }
    return {
        //main function to initiate the module
        init: function() {
        	$.fn.DataTable.ext.pager.numbers_length = 4;
            initPickers();
            handleRecords();
        }
    };
}();


$(document).ready(function() {
    let cookie = clearcookie;
    if (cookie == 'true') {
        $.removeCookie('ContactLeadsearch');
        $('#searchfilter').val('');
    }
});

$(window).on('load', function() {
    if ($.cookie('ContactLeadsearch')) {
        $('#searchfilter').val($.cookie('ContactLeadsearch'));
        $('#searchfilter').trigger('keyup');
    }
});

jQuery(document).ready(function() {
    $.ajaxSetup({
        headers: {
            'X-CSRF-Token': $('input[name="_token"]').val()
        }
    });
    TableDatatablesAjax.init();
});