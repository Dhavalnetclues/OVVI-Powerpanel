var gridRows = 0;
var grid;
var TableDatatablesAjax = function () {
    var initPickers = function () {
        //init date pickers
        $('.date-picker').datepicker({
            rtl: App.isRTL(),
            autoclose: true
        });
    }
    var handleRecords = function () {
        var action = $('#category_id').val();
        grid = new Datatable(); // showSearch
        if(!tableState){
          $.removeCookie('showSearch');
          $("#datatable_ajax").DataTable().state.clear();
          $("#datatable_ajax").DataTable().destroy();
        }
        if (tableState) {
            if($.cookie('showSearch')) {
                var showSearch = $.cookie('showSearch');
                  $('#searchfilter').val(showSearch);
                  grid.setAjaxParam("searchValue", showSearch);
                  $('#searchfilter').trigger('keyup');
             }       
        }
        grid.setAjaxParam("catValue", action);
        grid.init({
            src: $("#datatable_ajax"),
            onSuccess: function (grid, response) {
                gridRows = response.recordsTotal;
                if (response.recordsTotal < 1) {
                    $('.deleteMass').hide();
                } else {
                    $('.deleteMass').show();
                }
            },
            onError: function (grid) {
                // execute some code on network or other general error  
            },
            onDataLoad: function (grid) {
                // execute some code on ajax data load
                if ($('.pagination-panel .prev').hasClass('disabled')) {
                    $("#datatable_ajax tbody tr:first").find('.moveUp').hide();
                }
                if ($('.pagination-panel .next').hasClass('disabled')) {
                    $("#datatable_ajax tbody tr:last").find('.moveDwn').hide();
                }
                // $('.make-switch').bootstrapSwitch();
            },
            loadingMessage: 'Loading...',
            dataTable: { // here you can define a typical datatable settings from http://datatables.net/usage/options 
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
                    "bSortable": false
                }, {
                    "data": 1,
                    className: 'text-left',
                    "name": 'varTitle'
                }, {
                    "data": 2,
                    className: 'text-center',
                    "name": 'varAuthor'
                }, {
                    "data": 3,
                    className: 'text-center',
                    "bSortable": false
                },{
                    "data": 4,
                    className: 'text-center',
                    "bSortable": false
                }, {
                    "data": 5,
                    className: 'text-center',
                    "name": 'dtStartDateTime'
                }, {
                    "data": 6,
                    className: 'text-center',
                    "name": 'dtEndDateTime'
                }, {
                    "data": 7,
                    className: 'text-center',
                    "bSortable": false
                }, {
                    "data": 8,
                    className: 'text-center form-switch',
                    "bSortable": false
                }, {
                    "data": 9,
                    className: 'text-right',
                    "bSortable": false
                }],
                "ajax": {
                    "url": window.site_url + "/powerpanel/shows/get_list", // ajax source
                },
                "order": [
                    [5, "desc"]
                ]
            }
        });
        $(document).on('change', '#statusfilter', function (e) {
            e.preventDefault();
            var action = $('#statusfilter').val();
            var catValue = $('#category_id').val();
            grid.setAjaxParam("catValue", catValue);
            if (action != "") {
                grid.setAjaxParam("customActionType", "group_action");
                grid.setAjaxParam("statusValue", action);
                grid.setAjaxParam("id", grid.getSelectedRows());
                grid.getDataTable().ajax.reload();
            } else {
                grid.setAjaxParam("customActionType", "group_action");
                grid.setAjaxParam("statusValue", action);
                grid.setAjaxParam("id", grid.getSelectedRows());
            }
        });
        $(document).on('change', '#category_id', function (e) {
            e.preventDefault();
            var action = $('#category_id').val();
            if (action != "") {
                grid.setAjaxParam("customActionType", "group_action");
                grid.setAjaxParam("catValue", action);
                grid.setAjaxParam("id", grid.getSelectedRows());
                grid.getDataTable().ajax.reload();
            } else {
                grid.setAjaxParam("customActionType", "group_action");
                grid.setAjaxParam("catValue", action);
                grid.setAjaxParam("id", grid.getSelectedRows());
            }
        });
        $(document).on('keyup', '#searchfilter', function (e) {
            e.preventDefault();
            var action = $('#searchfilter').val();
            var catValue = $('#category_id').val();
            grid.setAjaxParam("catValue", catValue);
            if (action.length >= 2) {
                $.cookie('showSearch', action);
                grid.setAjaxParam("customActionType", "group_action");
                grid.setAjaxParam("searchValue", action);
                grid.setAjaxParam("id", grid.getSelectedRows());
                grid.getDataTable().ajax.reload();
            } else if (action.length < 1) {
                $.removeCookie('showSearch');
                grid.setAjaxParam("customActionType", "group_action");
                grid.setAjaxParam("searchValue", action);
                grid.setAjaxParam("id", grid.getSelectedRows());
                grid.getDataTable().ajax.reload();
            }
        });
        $(document).on("change", ".publish", function(event, state) {
            //e.preventDefault();
            var controller = $(this).data('controller');
            var alias = $(this).data('alias');
            var val = $(this).data('value');
            var url = site_url + '/' + controller + '/publish';
            $.ajax({
                url: url,
                data: {
                    alias: alias,
                    val: val
                },
                type: "POST",
                dataType: "HTML",
                success: function (data) {
                    var catValue = $('#category_id').val();
                    grid.setAjaxParam("catValue", catValue);
                    grid.getDataTable().ajax.reload(null, false);
                },
                error: function () {
                    console.log('error!');
                }
            });
        });
        $(document).on('click', '#showRange', function (e) {
            e.preventDefault();
            var action = {};
            action['from'] = $('#start_date').val();
            action['to'] = $('#end_date').val();
            var catValue = $('#category_id').val();
            grid.setAjaxParam("catValue", catValue);
            if (action['from'] != '' || action['to'] != '') {
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
        $(document).on('click', '#refresh', function (e) {
            $('#start_date').val('');
            $('#end_date').val('');
            grid.setAjaxParam("rangeFilter", '');
            grid.getDataTable().ajax.reload();
        });
        // handle group actionsubmit button click
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
jQuery(document).ready(function () {
    $.ajaxSetup({
        headers: {
            'X-CSRF-Token': $('input[name="_token"]').val()
        }
    });
    TableDatatablesAjax.init();
});

function makeFeatured(fid, status) {
    var ajaxurl = site_url + '/powerpanel/shows/makeFeatured';
    $.ajax({
        url: ajaxurl,
        data: {
            id: fid,
            featured: status
        },
        type: "POST",
        dataType: "json",
        success: function (data) { },
        complete: function () {
            var catValue = $('#category_id').val();
            grid.setAjaxParam("catValue", catValue);
            grid.getDataTable().ajax.reload(null, false);
        },
        error: function () {
            console.log('error!');
        }
    });
}