var gridRows = 0;
var grid = '';
var TableDatatablesAjax = function () {
    var initPickers = function () {
    }
    var handleRecords = function () {
        grid = new Datatable();
        grid.init({
            src: $("#workflow_datatable_ajax"),
            onSuccess: function (grid, response) {
                if (response.recordsTotal < 1) {
                    $('.deleteMass').hide();
                } else {
                    $('.deleteMass').show();
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
            onError: function (grid) {
                // execute some code on network or other general error  
            },
            onDataLoad: function (grid) {
                // execute some code on ajax data load
                // $('.make-switch').bootstrapSwitch();
            },
            loadingMessage: 'Loading...',
            dataTable: {
                // here you can define a typical datatable settings from http://datatables.net/usage/options 
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
                "bPaginate": false,
                "info": false,
                "ordering": false,
                "columns": [
                    {
                        "data": 0,
                        "name": 'varTitle',
                        className: 'text-left workflow_list_title',
                        'bSortable': false
                    },
                    {
                        "data": 1,
                        className: 'td_checker text-right workflow_list_title_icon',
                    }
                ],
                "ajax": {
                    "url": window.site_url + "/powerpanel/workflow/get_list", // ajax source
                },
                "order": [
                    [1, "desc"]
                ]
            }
        });
        $(document).on('change', '#statusfilter', function (e) {
            e.preventDefault();
            var action = $('#statusfilter').val();
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
                    grid.getDataTable().ajax.reload(null, false);
                },
                error: function () {
                    console.log('error!');
                }
            });
        });
        $(document).on('keyup', '#searchfilter', function (e) {
            e.preventDefault();
            var action = $('#searchfilter').val();
            if (action.length >= 2) {
                $.cookie('workflowearch', action);
                grid.setAjaxParam("customActionType", "group_action");
                grid.setAjaxParam("searchValue", action);
                grid.setAjaxParam("id", grid.getSelectedRows());
                grid.getDataTable().ajax.reload();
            } else if (action.length < 1) {
                $.removeCookie('workflowearch');
                grid.setAjaxParam("customActionType", "group_action");
                grid.setAjaxParam("searchValue", action);
                grid.setAjaxParam("id", grid.getSelectedRows());
                grid.getDataTable().ajax.reload();
            }
        });
        $(document).on('change', '#rolefilter', function (e) {
            e.preventDefault();
            var action = $('#rolefilter').val();
            if (action != "") {
                grid.setAjaxParam("customActionType", "group_action");
                grid.setAjaxParam("customRoleName", action);
                grid.setAjaxParam("id", grid.getSelectedRows());
                grid.getDataTable().ajax.reload();
            } else {
                grid.setAjaxParam("customActionType", "group_action");
                grid.setAjaxParam("customRoleName", action);
                grid.setAjaxParam("id", grid.getSelectedRows());
            }
        });

        $(document).on('change', '#categoriesfilter', function (e) {
            e.preventDefault();
            var action = $('#categoriesfilter').val();
            if (action != "") {
                grid.setAjaxParam("customActionType", "group_action");
                grid.setAjaxParam("customCategorieName", action);
                grid.setAjaxParam("id", grid.getSelectedRows());
                grid.getDataTable().ajax.reload();
            } else {
                grid.setAjaxParam("customActionType", "group_action");
                grid.setAjaxParam("customCategorieName", action);
                grid.setAjaxParam("id", grid.getSelectedRows());
            }
        });
        $(document).on('click', '#refresh', function (e) {
            $('#start_date').val('');
            $('#end_date').val('');
            grid.setAjaxParam("rangeFilter", '');
            grid.getDataTable().ajax.reload();
        });

        grid.setAjaxParam("customActionType", "group_action");
        grid.clearAjaxParams();
        grid.getDataTable().columns().iterator('column', function (ctx, idx) {
            $(grid.getDataTable().column(idx).header()).append('<span class="sort-icon"/>');
        });
    }
    return {
        init: function () {
            initPickers();
            handleRecords();
        }
    };
}();

$(window).on('load', function () {
    if ($.cookie('workflowearch')) {
        $('#searchfilter').val($.cookie('workflowearch'));
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
