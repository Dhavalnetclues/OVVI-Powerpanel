var gridRows = 0;
var grid = '';
var TableDatatablesAjax = function () {
    var handleRecords = function () {
        grid = new Datatable();
        grid.init({
            src: $("#datatable_ajax"),
            onSuccess: function (grid, response) {
                gridRows = response.recordsTotal;
                if (response.recordsTotal < 1) {
                    $('.deleteMass').hide();
                } else {
                    $('.deleteMass').show();
                }
                if (response.newRecordCount > 0) {
                    $('.newcounter').text(response.newRecordCount).show();
                } else {
                    $('.newcounter').hide();
                }
                 setTimeout(function(){
                    $.each(settingarray, function (index, value) {
                          if (index == 'P') {
                              $.each(value, function (index, columnid) {
                                  $('#datatable_ajax thead').find('.'+columnid).addClass("hidecolumn");
                                  $("#datatable_ajax tbody").find('tr').each(function (index, value) {
                                      $(this).find('.'+columnid).addClass("hidecolumn");
                                  });
                              });
                          }
                      });   
                }, 1200);
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
            dataTable: {// here you can define a typical datatable settings from http://datatables.net/usage/options
                // Uncomment below line("dom" parameter) to fix the dropdown overflow issue in the datatable cells. The default datatable layout
                // setup uses scrollable div(table-scrollable) with overflow:auto to enable vertical scroll(see: assets/global/scripts/datatable.js).
                // So when dropdowns used the scrollable div should be removed.
                //"dom": "<'row'<'col-md-8 col-sm-12'pli><'col-md-4 col-sm-12'<'table-group-actions pull-right'>>r>t<'row'<'col-md-8 col-sm-12'pli><'col-md-4 col-sm-12'>>",
                "dom": "t <'gridjs-footer' <'gridjs-pagination'i <'gridjs-pages'p>>>",
                "deferRender": true,
                // "stateSave": true, // save datatable state(pagination, sort, etc) in cookie.
                // "lengthMenu": [
                //     [10, 20, 50, 100],
                //     [10, 20, 50, 100] // change per page values here
                // ],
                "pageLength": 20, // default record count per page
                //Code for sorting
                "serverSide": true,
                "lengthChange": false,
                "pagingType": "simple_numbers",
                "language": {
                    "info": '<div role="status" aria-live="polite" class="gridjs-summary" title="Page 1 of 2">Showing <b>_START_</b> to <b>_END_</b> of <b>_TOTAL_</b> results</div>',
                },
                "columns": [{
                        "data": 0,
                        className: 'text-center',
                        "bSortable": false
                    },{
                        "data": 1,
                        "bSortable": false
                    }, {
                        "data": 2,
                        className: 'text-left Department_title_P_2 mob-show_div',
                        "name": 'varTitle',
                        "bSortable": true
                    }, {
                        "data": 3,
                        className: 'text-left Department_email_P_3',
                        "name": 'varEmail',
                        "bSortable": true
                    }, {
                        "data": 4,
                        className: 'text-left Department_sdate_P_4',
                        "name": 'dtDateTime',
                        "bSortable": true
                    }, {
                        "data": 5,
                        className: 'text-left Department_edate_P_5',
                        "name": 'dtEndDateTime',
                        "bSortable": true
                    },
                    {
                        "data": 6,
                        className: 'text-left Department_order_P_6',
                        "name": 'intDisplayOrder'
                    }, {
                        "data": 7,
                        className: 'text-left form-switch Department_publish_P_7',
                        "bSortable": false
                    }, {
                        "data": 8,
                        className: 'text-right Department_dactions_P_8 last_td_action mob-show_div',
                        "bSortable": false
                    }, {
                        "data": 9,
                        className: 'text-left',
                        "bSortable": false
                    }],
                "columnDefs": [{
                        "targets": [1,9],
                        "visible": false,
                        "searchable": false
                    }],
                "ajax": {
                    "url": window.site_url + "/powerpanel/department/get_list", // ajax source
                },
                'fnCreatedRow': function (nRow, aData, iDataIndex) {
                    $(nRow).attr('data-order', aData[9]);
                },
                "order": [
                    [6, "asc"]
                ]
            }
        });
        $('#datatable_ajax tbody').on('click', '.moveDwn', function () {
            var order = $(this).data('order');
            exOrder = order + 1;
            reorder(order, exOrder);
        });
        $('#datatable_ajax tbody').on('click', '.moveUp', function () {
            var order = $(this).data('order');
            exOrder = order - 1;
            reorder(order, exOrder);
        });
        $(document).on('keyup', '#searchfilter', function (e) {
            e.preventDefault();
            var action = $('#searchfilter').val();
            if (action.length >= 2) {
                $.cookie('departmentsearch', action);
                grid.setAjaxParam("customActionType", "group_action");
                grid.setAjaxParam("searchValue", action);
                grid.setAjaxParam("id", grid.getSelectedRows());
                grid.getDataTable().ajax.reload();
            } else {
                $.removeCookie('departmentsearch');
                grid.setAjaxParam("customActionType", "group_action");
                grid.setAjaxParam("searchValue", action);
                grid.setAjaxParam("id", grid.getSelectedRows());
                grid.getDataTable().ajax.reload();
            }
        });
        $(document).on('change', '#statusfilter', function (e) {
            e.preventDefault();
            var action = $('#statusfilter').val();
            if (action != " ") {
                //$.cookie('ServiceCategoryStatus',action);
            } else {
                //$.removeCookie('ServiceCategoryStatus');
            }
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
    
        if (!showChecker) {
            grid.getDataTable().column(1).visible(false);
            grid.getDataTable().column(6).visible(false);
            grid.getDataTable().column(7).visible(false);
        } else {
             grid.getDataTable().column(1).visible(false);
             grid.getDataTable().column(6).visible(true);
            grid.getDataTable().column(7).visible(true);
        }
        
        grid.setAjaxParam("customActionType", "group_action");
        grid.clearAjaxParams();
        grid.getDataTable().columns().iterator('column', function (ctx, idx) {
            $(grid.getDataTable().column(idx).header()).append('<span class="sort-icon"/>');
        });
    }
    return {
        //main function to initiate the module
        init: function () {
            handleRecords();
        }
    };
}();
var grid1Rows = 0;
var grid1 = '';
var TableDatatablesAjax1 = function () {
   
    var handleRecords1 = function () {
        grid1 = new Datatable();
        grid1.init({
            src: $("#datatable_ajax_approved"),
            onSuccess: function (grid1, response) {
                gridRows1 = response.recordsTotal;
                if (response.recordsTotal < 1) {
                    $('.deleteMass').hide();
                } else {
                    $('.deleteMass').show();
                }
                if (response.newRecordCount > 0) {
                    $('.newcounter').text(response.newRecordCount).show();
                } else {
                    $('.newcounter').hide();
                }
                setTimeout(function(){
                    $.each(settingarray, function (index, value) {
                          if (index == 'A') {
                              $.each(value, function (index, columnid) {
                                  $('#datatable_ajax_approved thead').find('.'+columnid).addClass("hidecolumn");
                                  $("#datatable_ajax_approved tbody").find('tr').each(function (index, value) {
                                      $(this).find('.'+columnid).addClass("hidecolumn");
                                  });
                              });
                          }
                      });   
                }, 1500);
                // grid:        grid object
                // response:    json object of server side ajax response
                // execute some code after table records loaded
            },
            onError: function (grid1) {
                // execute some code on network or other general error
            },
            onDataLoad: function (grid1) {
                // execute some code on ajax data load
                // $('.make-switch').bootstrapSwitch();
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
                        "bSortable": false
                    },{
                        "data": 1,
                        "bSortable": true,
                        className: 'text-left Department_title_A_2 mob-show_div',
                        "name": 'varTitle'
                    },{
                        "data": 2,
                        "bSortable": true,
                        className: 'text-center Department_email_A_3',
                        "name": 'varEmail'
                    }, {
                        "data": 3,
                        className: 'text-center Department_sdate_A_4',
                        "name": 'dtDateTime',
                        "bSortable": false
                    }, {
                        "data": 4,
                        className: 'text-center Department_edate_A_5',
                        "name": 'dtEndDateTime',
                        "bSortable": false
                    },{
                        "data": 5,
                        className: 'text-right Department_dactions_A_8 last_td_action mob-show_div ',
                        "bSortable": false
                    }],
               "columnDefs": [{
                        "targets": [0],
                        "visible": false,
                        "searchable": false
                    }],
                "ajax": {
                    "url": window.site_url + "/powerpanel/department/get_list_New", // ajax source
                },
                'fnCreatedRow': function (nRow, aData, iDataIndex) {
                    $(nRow).attr('data-order', aData[5]);
                },
                "order": [
                    [0, "asc"]
                ]
            }
        });
        $(document).on('keyup', '#searchfilter', function (e) {
            e.preventDefault();
            var action = $('#searchfilter').val();
            if (action.length >= 2) {
                $.cookie('departmentsearch', action);
                grid1.setAjaxParam("customActionType", "group_action");
                grid1.setAjaxParam("searchValue", action);
                grid1.setAjaxParam("id", grid1.getSelectedRows());
                grid1.getDataTable().ajax.reload();
            } else {
                $.removeCookie('departmentsearch');
                grid1.setAjaxParam("customActionType", "group_action");
                grid1.setAjaxParam("searchValue", action);
                grid1.setAjaxParam("id", grid1.getSelectedRows());
                grid1.getDataTable().ajax.reload();
            }
        });
        $(document).on('change', '#statusfilter', function (e) {
            e.preventDefault();
            var action = $('#statusfilter').val();
            if (action != " ") {
                //$.cookie('ServiceCategoryStatus',action);
            } else {
                //$.removeCookie('ServiceCategoryStatus');
            }
            if (action != "") {
                grid1.setAjaxParam("customActionType", "group_action");
                grid1.setAjaxParam("statusValue", action);
                grid1.setAjaxParam("id", grid1.getSelectedRows());
                grid1.getDataTable().ajax.reload();
            } else {
                grid1.setAjaxParam("customActionType", "group_action");
                grid1.setAjaxParam("statusValue", action);
                grid1.setAjaxParam("id", grid1.getSelectedRows());
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
                    grid1.getDataTable().ajax.reload(null, false);
                },
                error: function () {
                    console.log('error!');
                }
            });
        });
        $('a[data-toggle="tab"][id="MenuItem1"]').on('shown.bs.tab', function (e) {
            e.preventDefault();
            grid.setAjaxParam("id", grid.getSelectedRows());
            grid.getDataTable().ajax.reload();
        });
        $('a[data-toggle="tab"][id="MenuItem2"]').on('shown.bs.tab', function (e) {
            e.preventDefault();
            grid1.setAjaxParam("id", grid1.getSelectedRows());
            grid1.getDataTable().ajax.reload();
        });
       
        grid1.setAjaxParam("customActionType", "group_action");
        grid1.clearAjaxParams();
        grid1.getDataTable().columns().iterator('column', function (ctx, idx) {
            $(grid1.getDataTable().column(idx).header()).append('<span class="sort-icon"/>');
        });
    }
    return {
        //main function to initiate the module
        init: function () {
            handleRecords1();
        }
    };
}();
$(window).on('load', function () {
    if ($.cookie('departmentsearch')) {
        $('#searchfilter').val($.cookie('departmentsearch'));
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
    //TableDatatablesAjax1.init();
});
$('a[data-toggle="tab"][id="MenuItem2"]').on('shown.bs.tab', function (e) {
    if (!$.fn.DataTable.isDataTable('#datatable_ajax_approved')) {
        TableDatatablesAjax1.init();
    }
});
function reorder(curOrder, excOrder) {
    var ajaxurl = site_url + '/powerpanel/department/reorder';
    $.ajax({
        url: ajaxurl,
        data: {
            order: curOrder,
            exOrder: excOrder
        },
        type: "POST",
        dataType: "HTML",
        success: function (data) {
        },
        complete: function () {
            grid.getDataTable().ajax.reload(null, false);
        },
        error: function () {
            console.log('error!');
        }
    });
}