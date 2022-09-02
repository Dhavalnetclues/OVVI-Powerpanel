var gridRows = 0;   //-- Main Tab
var grid;
var TableDatatablesAjax = function() {
    var handleRecords = function() {
        var grid = new Datatable();
        grid.init({
            src: $("#datatable_ajax"),
            onSuccess: function(grid, response) {
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
            },
            onError: function(grid) {
                // execute some code on network or other general error
            },
            onDataLoad: function(grid) {
                if ($('.pagination-panel .prev').hasClass('disabled')) {
                    $("#datatable_ajax tbody tr:first").find('.moveUp').hide();
                }
                if ($('.pagination-panel .next').hasClass('disabled')) {
                    $("#datatable_ajax tbody tr:last").find('.moveDwn').hide();
                }
                // $('.make-switch').bootstrapSwitch();
                // execute some code on ajax data load
            },
            loadingMessage: 'Loading...',
            dataTable: {
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
                        "class": 'td_checker',
                        'bSortable': false
                    }, {
                        "data": 1,
                        "class": 'text-left mob-show_div',
                        'name': 'varTitle',
                        'bSortable': false
                    }, {
                        "data": 2,
                        "class": 'text-left',
                        'name': 'varEmail',
                        'bSortable': false
                    }, {
                        "data": 3,
                        "class": 'text-center',
                        'bSortable': false
                    }, {
                        "data": 4,
                        "class": 'text-center',
                        'name': 'created_at',
                        'bSortable': false
                    }, {
                        "data": 5,
                        'bSortable': false,
                        'class': 'text-center form-switch Alerts_publish_P_7',
                    }, {
                        "data": 6,
                        "class": 'text-right last_td_action mob-show_div',
                        'bSortable': false
                    }
                ],
                "ajax": {
                    "url": window.site_url + "/powerpanel/contact-info/get_list", // ajax source
                },
                'fnCreatedRow': function(nRow, aData, iDataIndex) {
                    //console.log(aData);
                    // $(nRow).attr('id', aData[1]);
                    // $(nRow).attr('data-order', aData[5]);
                },
                "order": [
                    [4, "desc"]
                ],
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
        	$.fn.DataTable.ext.pager.numbers_length = 4;
            handleRecords();
        }
    };
}();

$(document).ready(function() {
    let cookie = clearcookie;
    if (cookie == 'true') {
        $.removeCookie('ContactDetailsearch');
        $('#searchfilter').val('');
    }
});

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