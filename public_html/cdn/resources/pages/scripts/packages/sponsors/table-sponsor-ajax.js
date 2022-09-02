var gridRows = 0;
var grid;
var TableDatatablesAjax = function() {
    var handleRecords = function() {
        var action = $('#category_id').val();
        grid = new Datatable(); // searchSponser
        if(!tableState){
              $.removeCookie('searchSponser');
              $("#sponsor_datatable_ajax").DataTable().state.clear();
              $("#sponsor_datatable_ajax").DataTable().destroy();
        }
        if (tableState) {
              if($.cookie('searchSponser')) {
                var searchSponser = $.cookie('searchSponser');
                $('#searchfilter').val(searchSponser);
                grid.setAjaxParam("searchValue", searchSponser);
                $('#searchfilter').trigger('keyup');
           }       
          }
        grid.setAjaxParam("catValue", action);
        grid.init({
            src: $("#sponsor_datatable_ajax"),
            onSuccess: function(grid, response) {
                if (response.recordsTotal < 1) {
                    $('.deleteMass').hide();
                } else {
                    $('.deleteMass').show();
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
                // if ($('.pagination-panel .prev').hasClass('disabled')) {
                //     $("#sponsor_datatable_ajax tbody tr:first").find('.moveUp').hide();
                // }
                // if ($('.pagination-panel .next').hasClass('disabled')) {
                //     $("#sponsor_datatable_ajax tbody tr:last").find('.moveDwn').hide();
                // }
                // $('.make-switch').bootstrapSwitch();
            },
            loadingMessage: 'Loading...',
            dataTable: { // here you can define a typical datatable settings from http://datatables.net/usage/options 
                // Uncomment below line("dom" parameter) to fix the dropdown overflow issue in the datatable cells. The default datatable layout
                // setup uses scrollable div(table-scrollable) with overflow:auto to enable vertical scroll(see: assets/global/scripts/datatable.js). 
                // So when dropdowns used the scrollable div should be removed. 
                //"dom": "<'row'<'col-md-8 col-sm-12'pli><'col-md-4 col-sm-12'<'table-group-actions pull-right'>>r>t<'row'<'col-md-8 col-sm-12'pli><'col-md-4 col-sm-12'>>",
                "bStateSave": true, // save datatable state(pagination, sort, etc) in cookie.
                "lengthMenu": [
                    [10, 20, 50, 100],
                    [10, 20, 50, 100] // change per page values here
                ],
                "pageLength": 100, // default record count per page
                //Code for sorting 
                "serverSide": true,
                "columns": [{
                    "data": 0,
                    className: 'td_checker reorder',
                    'bSortable': false
                }, {
                    "data": 1,
                    className: 'text-left',
                    'name': 'varTitle',
                    'bSortable': true
                }, {
                    "data": 2,
                    className: 'text-center',
                    'bSortable': false
                }, {
                    "data": 3,
                    className: 'text-center',
                    'bSortable': false
                }, {
                    "data": 4,
                    className: 'text-center',
                    "name": 'intDisplayOrder'
                }, {
                    "data": 5,
                    className: 'text-center',
                    'bSortable': false
                }, {
                    "data": 6,
                    className: 'text-center form-switch',
                    'bSortable': false
                }, {
                    "data": 7,
                    className: 'text-right',
                    'bSortable': false
                }, {
                    "data": 8,
                    'bSortable': false
                }],
                "columnDefs": [{
                    "targets": [8],
                    "visible": false,
                    "searchable": false
                }],
                "ajax": {
                    "url": window.site_url + "/powerpanel/sponsor/get_list", // ajax source
                },
                'fnCreatedRow': function(nRow, aData, iDataIndex) {
                    $(nRow).attr('data-order', aData[8]);
                },
                "order": [
                    [4, "desc"]
                ]
            }
        });
        $('#sponsor_datatable_ajax tbody').on('click', '.moveDwn', function() {
            var order = $(this).data('order');
            //var exOrder = $('#sponsor_datatable_ajax tbody').find('tr[data-order=' + order + ']').next().data('order');
            exOrder = order - 1;
            reorder(order, exOrder);
        });
        $('#sponsor_datatable_ajax tbody').on('click', '.moveUp', function() {
            var order = $(this).data('order');
            //var exOrder = $('#sponsor_datatable_ajax tbody').find('tr[data-order=' + order + ']').prev().data('order');
            exOrder = order + 1;
            reorder(order, exOrder);
        });
        $(document).on('keyup', '#searchfilter', function(e) {
            e.preventDefault();
            var action = $('#searchfilter').val();
            var catValue = $('#category_id').val();
            grid.setAjaxParam("catValue", catValue);
            if (action.length >= 2) {
                $.cookie('searchSponser', action);
                grid.setAjaxParam("customActionType", "group_action");
                grid.setAjaxParam("searchValue", action);
                grid.setAjaxParam("id", grid.getSelectedRows());
                grid.getDataTable().ajax.reload();
            } else if (action.length < 1) {
                $.removeCookie('searchSponser');
                grid.setAjaxParam("customActionType", "group_action");
                grid.setAjaxParam("searchValue", action);
                grid.setAjaxParam("id", grid.getSelectedRows());
                grid.getDataTable().ajax.reload();
            }
        });
        $(document).on('change', '#statusfilter', function(e) {
            e.preventDefault();
            var action = $('#statusfilter').val();
            var catValue = $('#category_id').val();
            grid.setAjaxParam("catValue", catValue);
            if (action != "") {
                grid.setAjaxParam("customActionType", "group_action");
                grid.setAjaxParam("statusFilter", action);
                grid.setAjaxParam("id", grid.getSelectedRows());
                grid.getDataTable().ajax.reload();
            } else {
                grid.setAjaxParam("customActionType", "group_action");
                grid.setAjaxParam("statusFilter", action);
                grid.setAjaxParam("id", grid.getSelectedRows());
            }
        });
        $(document).on('change', '#category_id', function(e) {
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
                success: function(data) {
                	var catValue = $('#category_id').val();
            			grid.setAjaxParam("catValue", catValue);
                    grid.getDataTable().ajax.reload();
                },
                error: function() {
                    console.log('error!');
                }
            });
        });
        grid.setAjaxParam("customActionType", "group_action");
        // grid.getDataTable().ajax.reload();
        grid.clearAjaxParams();
        grid.getDataTable().columns().iterator('column', function(ctx, idx) {
                    $(grid.getDataTable().column(idx).header()).append('<span class="sort-icon"/>');
            });
    }
    return {
        //main function to initiate the module
        init: function() {
            handleRecords();
        }
    };
}();

jQuery(document).ready(function() {
    if ($.cookie('statusSponser')) {
        $("#statusfilter option[value='" + $.cookie('statusSponser') + "']").attr("selected", "selected");
    } else {
        $("#statusfilter option[value=' ']").attr("selected", "selected");
    }
    $.ajaxSetup({
        headers: {
            'X-CSRF-Token': $('input[name="_token"]').val()
        }
    });
    TableDatatablesAjax.init();
});

function reorder(curOrder, excOrder) {
    var ajaxurl = site_url + '/powerpanel/sponsor/reorder';
    $.ajax({
        url: ajaxurl,
        data: {
            order: curOrder,
            exOrder: excOrder
        },
        type: "POST",
        dataType: "HTML",
        success: function(data) {},
        complete: function() {
            grid.getDataTable().ajax.reload(null, false);
        },
        error: function() {
            console.log('error!');
        }
    });
}