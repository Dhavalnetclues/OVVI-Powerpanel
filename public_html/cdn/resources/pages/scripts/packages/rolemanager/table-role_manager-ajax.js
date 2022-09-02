var gridRows = 0;
var grid = "";
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
        grid.init({
            src: $("#datatable_ajax"),
            onSuccess: function(grid, response) {
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
                // grid:        grid object
                // response:    json object of server side ajax response
                // execute some code after table records loaded
            },
            onError: function(grid) {
                // execute some code on network or other general error
            },
            onDataLoad: function(grid) {
                // execute some code on ajax data load
                $(document).ready(function () {
                    var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
                    var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
                        return new bootstrap.Tooltip(tooltipTriggerEl)
                    })
                });
            },
            loadingMessage: 'Loading...',
            dataTable: { // here you can define a typical datatable settings from http://datatables.net/usage/options
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
                "columns": [{
                    "data": 0,
                    'class': ' text-center',
                    'bSortable': false
                }, {
                    "data": 1,
                    "class": 'text-left mob-show_div',
                    'name': 'name'
                }, {
                    "data": 2,
                    "class": 'text-center',
                    'name': 'chrIsAdmin'
                }, {
                    "data": 3,
                    "class": 'text-center',
                    'name': 'updated_at'
                }, {
                    "data": 4,
                    "class": 'text-right last_td_action mob-show_div',
                    'bSortable': false
                },],
                "ajax": {
                    "url": window.site_url + "/powerpanel/roles/get_list", // ajax source
                },
                "order": [
                        [3, "desc"]
                    ] // set first column as a default sort by asc
            }
        });

        // handle group actionsubmit button click
        $(document).on('keyup', '#searchfilter', function(e) {
            e.preventDefault();
            var action = $('#searchfilter').val();
            if (action.length >= 2) {
                //$.cookie('Rolesearch',action);
                grid.setAjaxParam("customActionType", "group_action");
                grid.setAjaxParam("searchValue", action);
                grid.setAjaxParam("id", grid.getSelectedRows());
                grid.getDataTable().ajax.reload();
            } else if (action.length < 1) {
                //$.removeCookie('Rolesearch');
                grid.setAjaxParam("customActionType", "group_action");
                grid.setAjaxParam("searchValue", action);
                grid.setAjaxParam("id", grid.getSelectedRows());
                grid.getDataTable().ajax.reload();
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
        $.removeCookie('Rolesearch');
        $('#searchfilter').val('');
    }
});

$(window).on('load', function() {
    if ($.cookie('Rolesearch')) {
        $('#searchfilter').val($.cookie('Rolesearch'));
        $('#searchfilter').trigger('keyup');
    }
});

jQuery(document).ready(function() {
    TableDatatablesAjax.init();
});