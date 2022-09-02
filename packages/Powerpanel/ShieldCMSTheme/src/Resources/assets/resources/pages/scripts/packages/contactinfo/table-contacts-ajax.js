var grid;
var TableDatatablesAjax = function() {
    var handleRecords = function() {
        var grid = new Datatable();
        grid.init({
            src: $("#contacts_datatable_ajax"),
            onSuccess: function(grid, response) {
                if (response.recordsTotal < 1) {
                    $('.deleteMass').hide();
                } else {
                    $('.deleteMass').show();
                }
            },
            onError: function(grid) {
                // execute some code on network or other general error  
            },
            onDataLoad: function(grid) {
                if ($('.pagination-panel .prev').hasClass('disabled')) {
                    $("#contacts_datatable_ajax tbody tr:first").find('.moveUp').hide();
                }
                if ($('.pagination-panel .next').hasClass('disabled')) {
                    $("#contacts_datatable_ajax tbody tr:last").find('.moveDwn').hide();
                }
                $('.make-switch').bootstrapSwitch();
                // execute some code on ajax data load
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
                        className: 'td_checker',
                        'bSortable': false
                    },
                    {
                        "data": 1,
                        className: 'text-center mob-show_div',
                        'name': 'varTitle',
                        'bSortable': false
                    }, {
                        "data": 2,
                        className: 'text-center',
                        'name': 'varEmail',
                        'bSortable': false
                    }, {
                        "data": 3,
                        className: 'text-center',
                        'bSortable': false
                    }, {
                        "data": 4,
                        className: 'text-center',
                        'name': 'created_at',
                        'bSortable': false
                    },
                    {
                        "data": 5,
                        'bSortable': false,
                        'class': 'text-center publish_switch Alerts_publish_P_7',
                    },
                    {
                        "data": 6,
                        className: 'text-right last_td_action mob-show_div',
                        'bSortable': false
                    }
                ],
                "columnDefs": [{
                    "targets": [7],
                    "visible": false,
                    "searchable": false
                }],
                "ajax": {
                    "url": window.site_url + "/powerpanel/contact-info/get_list", // ajax source
                },
                'fnCreatedRow': function(nRow, aData, iDataIndex) {
                    //console.log(aData);
                    $(nRow).attr('id', aData[1]);
                    $(nRow).attr('data-order', aData[5]);
                },
                "order": [
                    [0, "desc"]
                ], // set first column as a default sort by asc
            }
        });
        $(document).on("switchChange.bootstrapSwitch", ".publish", function(event, state) {
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
                    grid.getDataTable().ajax.reload(null, false);
                },
                error: function() {
                    console.log('error!');
                }
            });
        });
        $('#contacts_datatable_ajax tbody').on('click', '.moveDwn', function() {
            var order = $(this).data('order');
            var exOrder = $('#contacts_datatable_ajax tbody').find('tr[data-order=' + order + ']').next().data('order');
            exOrder = (exOrder == undefined) ? order + 1 : exOrder;
            reorder(order, exOrder, grid);
        });
        $('#contacts_datatable_ajax tbody').on('click', '.moveUp', function() {
            var order = $(this).data('order');
            var exOrder = $('#contacts_datatable_ajax tbody').find('tr[data-order=' + order + ']').prev().data('order');
            exOrder = (exOrder == undefined) ? order - 1 : exOrder;
            reorder(order, exOrder, grid);
        });
        $(document).on('change', '#statusfilter', function(e) {
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
        //This code for search filter
        $(document).on('keyup', '#searchfilter', function(e) {
            e.preventDefault();
            var action = $('#searchfilter').val();
            if (action.length >= 2) {
                $.cookie('ContactDetailsearch', action);
                grid.setAjaxParam("customActionType", "group_action");
                grid.setAjaxParam("searchValue", action);
                grid.setAjaxParam("id", grid.getSelectedRows());
                grid.getDataTable().ajax.reload();
            } else if (action.length < 1) {
                $.removeCookie('ContactDetailsearch');
                grid.setAjaxParam("customActionType", "group_action");
                grid.setAjaxParam("searchValue", action);
                grid.setAjaxParam("id", grid.getSelectedRows());
                grid.getDataTable().ajax.reload();
            }
        });
        grid.setAjaxParam("customActionType", "group_action");
        grid.clearAjaxParams();
        /*grid.getDataTable().columns().iterator('column', function(ctx, idx) {
         $(grid.getDataTable().column(idx).header()).append('<span class="sort-icon"/>');
         });*/
    }
    return {
        //main function to initiate the module
        init: function() {
            handleRecords();
        }
    };
}();
$(window).on('load', function() {
    if ($.cookie('ContactDetailsearch')) {
        $('#searchfilter').val($.cookie('ContactDetailsearch'));
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
    $(".datatable_head").hide();
});

function reorder(curOrder, excOrder, grid) {
    var ajaxurl = site_url + '/powerpanel/contact-info/reorder';
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