var TableDatatablesAjax = function () {
    var handleRecords = function () {
        var grid = new Datatable();
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
                if(response.recordsTotal < 20) {
                    $('.gridjs-pages').hide();
                } else {
                    $('.gridjs-pages').show();
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
                    })
                });
                // execute some code on ajax data load
            },
            loadingMessage: 'Loading...',
            dataTable: {
                "dom": "t <'gridjs-footer' <'gridjs-pagination'i <'gridjs-pages'p>>>",
                "deferRender": true,
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
                "serverSide": true,
                "lengthChange": false,
                "pagingType": "simple_numbers",
                "language": {
                    "info": '<div role="status" aria-live="polite" class="gridjs-summary">Showing <b>_START_</b> to <b>_END_</b> of <b>_TOTAL_</b> results</div>', // title="Page 1 of 2"
                },
                "columns": [
                    {"data": 0, "class": 'text-center td_checker', "bSortable": false},
                    {"data": 1, "class": 'text-left', "name": 'varCountry_flag', "bSortable": false},
                    {"data": 2, "class": 'text-left', "name": 'varCountry_name', "bSortable": true},
                    {"data": 3, "class": 'text-left', "name": 'name', "bSortable": false},
                    {"data": 4, "class": 'text-left', "name": 'email', "bSortable": false},
                    {"data": 5, "class": 'text-center', "name": 'varIpAddress', "bSortable": false},
                    {"data": 6, "class": 'text-center', "name": 'created_at', "bSortable": true},
                    {"data": 7, "class": 'text-center', "name": 'updated_at', "bSortable": true},
                ],
                "ajax": {
                    "url": window.site_url + "/powerpanel/login-history/get_list", // ajax source
                },
                "order": [
                    [6, "desc"]
                ]// set first column as a default sort by asc
            }
        });

        $(document).on('keyup', '#searchfilter', function (e) {
            e.preventDefault();
            var action = $('#searchfilter').val();
            if (action.length >= 2) {
                //$.cookie('ContactLeadsearch',action);
                grid.setAjaxParam("customActionType", "group_action");
                grid.setAjaxParam("searchValue", action);
                grid.setAjaxParam("id", grid.getSelectedRows());
                grid.getDataTable().ajax.reload();
            } else if (action.length < 1) {
                //$.removeCookie('ContactLeadsearch');
                grid.setAjaxParam("customActionType", "group_action");
                grid.setAjaxParam("searchValue", action);
                grid.setAjaxParam("id", grid.getSelectedRows());
                grid.getDataTable().ajax.reload();
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