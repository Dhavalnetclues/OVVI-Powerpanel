var gridRows = 0;
var grid;
var TableDatatablesAjax = function() {
    var initPickers = function() {}
    var handleRecords = function() {
        var action = $('#category_id').val();
        grid = new Datatable();
        grid.setAjaxParam("catValue", action);
        grid.init({
            src: $("#datatable_ajax"),
            onSuccess: function(grid, response) {
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
                setTimeout(function() {
                    $.each(settingarray, function(index, value) {
                        if (index == 'P') {
                            $.each(value, function(index, columnid) {
                                $('#datatable_ajax thead').find('.' + columnid).addClass("hidecolumn");
                                $("#datatable_ajax tbody").find('tr').each(function(index, value) {
                                    $(this).find('.' + columnid).addClass("hidecolumn");
                                });
                            });
                        }
                    });
                }, 1200);
            },
            onError: function(grid) {
                // execute some code on network or other general error  
            },
            onDataLoad: function(grid) {
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
                "dom": "t <'gridjs-footer' <'gridjs-pagination'i <'gridjs-pages'p>>>",
                "deferRender": true,
                // "bStateSave": false, // save datatable state(pagination, sort, etc) in cookie.
                // "lengthMenu": [
                //     [10, 20, 50, 100],
                //     [10, 20, 50, 100] // change per page values here
                // ],
                "pageLength": 20, // default record count per page
                //Code for sorting
                "stateSave": true,
                "lengthChange": false,
                "pagingType": "simple_numbers",
                "language": {
                    "info": '<div role="status" aria-live="polite" class="gridjs-summary" title="Page 1 of 2">Showing <b>_START_</b> to <b>_END_</b> of <b>_TOTAL_</b> results</div>',
                },
                "columns": [{
                        "data": 0,
                        'class': 'text-center',
                        "bSortable": false
                    }, {
                        "data": 1,
                        'class': 'text-left mob-show_div',
                        'bSortable': false
                    }, {
                        "data": 2,
                        "name": 'varTitle',
                        className: 'text-left DecisionCategory_title_P_2 mob-show_div',
                        "bSortable": false
                    }, {
                        "data": 3,
                        "bSortable": false,
                        className: 'text-left DecisionCategory_cat_P_3'
                    },
                    //  {
                    //     "data": 4,
                    //     "name": 'dtDateTime',
                    //     className: 'text-center DecisionCategory_sdate_P_4'
                    // }, {
                    //     "data": 5,
                    //     "name": 'dtEndDateTime',
                    //     className: 'text-center DecisionCategory_edate_P_5'
                    // },
                    {
                        "data": 4,
                        "name": 'intDisplayOrder',
                        className: 'text-left DecisionCategory_order_P_7'
                    }, {
                        "data": 5,
                        "bSortable": false,
                        className: 'text-left form-switch DecisionCategory_publish_P_8'
                    },
                    {
                        "data": 6,
                        "bSortable": false,
                        className: 'text-right last_td_action DecisionCategory_dactions_P_9 mob-show_div'
                    }
                ],
                "columnDefs": [{
                    "targets": [6],
                    "visible": false,
                    "searchable": false
                }],
                "ajax": {
                    "url": window.site_url + "/powerpanel/decision-category/get_list", // ajax source
                },
                'fnCreatedRow': function(nRow, aData, iDataIndex) {
                    $(nRow).attr('data-order', aData[6]);
                },
                "order": [
                    [4, "asc"]
                ]
            }
        });
        $('#datatable_ajax tbody').on('click', '.moveDwn', function() {
            var order = $(this).data('order');
            var exOrder = order + 1;
            var parentRecordId = $(this).attr('data-parentRecordId');
            reorder(order, exOrder, parentRecordId);
        });
        $('#datatable_ajax tbody').on('click', '.moveUp', function() {
            var order = $(this).data('order');
            var exOrder = order - 1;
            exOrder = (exOrder == 0) ? 1 : exOrder;
            var parentRecordId = $(this).attr('data-parentRecordId');
            reorder(order, exOrder, parentRecordId);
        });
        generateHeadfilterEvents(grid);
        $(document).on('change', '#statusfilter', function(e) {
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
        $(document).on('change', '#sectorfilter', function(e) {
            e.preventDefault();
            var action = $('#sectorfilter').val();

            if (action != "") {
                grid.setAjaxParam("customActionType", "group_action");
                grid.setAjaxParam("sectorValue", action);
                grid.setAjaxParam("id", grid.getSelectedRows());
                grid.getDataTable().ajax.reload();
            } else {
                grid.setAjaxParam("customActionType", "group_action");
                grid.setAjaxParam("sectorValue", action);
                grid.setAjaxParam("id", grid.getSelectedRows());
            }
        });
          $(document).on('change', '#categoriesfilter', function(e) {
            e.preventDefault();
            var action = $('#categoriesfilter').val();

            if (action != "") {
                grid.setAjaxParam("customActionType", "group_action");
                grid.setAjaxParam("parentcatValue", action);
                grid.setAjaxParam("id", grid.getSelectedRows());
                grid.getDataTable().ajax.reload();
            } else {
                grid.setAjaxParam("customActionType", "group_action");
                grid.setAjaxParam("parentcatValue", action);
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
                    grid.getDataTable().ajax.reload(null, false);
                },
                error: function() {
                    console.log('error!');
                }
            });
        });
        $(document).on('keyup', '#searchfilter', function(e) {
            e.preventDefault();
            var action = $('#searchfilter').val();
            if (action.length >= 2) {
                $.cookie('ServiceCategorySearch', action);
                grid.setAjaxParam("customActionType", "group_action");
                grid.setAjaxParam("searchValue", action);
                grid.setAjaxParam("id", grid.getSelectedRows());
                grid.getDataTable().ajax.reload();
            } else if (action.length < 1) {
                $.removeCookie('ServiceCategorySearch');
                grid.setAjaxParam("customActionType", "group_action");
                grid.setAjaxParam("searchValue", action);
                grid.setAjaxParam("id", grid.getSelectedRows());
                grid.getDataTable().ajax.reload();
            }
        });

        $('a[data-toggle="tab"][id="MenuItem1"]').on('shown.bs.tab', function(e) {
            $("#hidefilter").show();
            e.preventDefault();
            grid.setAjaxParam("id", grid.getSelectedRows());
            grid.getDataTable().ajax.reload();
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
            initPickers();
            handleRecords();
        }
    };
}();

var grid1;
var TableDatatablesAjaxApproval = function() {
    var initPickers = function() {}
    var handleRecords = function() {
        var action = $('#category_id').val();
        grid1 = new Datatable();
        grid1.setAjaxParam("catValue", action);
        grid1.init({
            src: $("#datatable_ajax_approved"),
            onSuccess: function(grid1, response) {
                grid1Rows = response.recordsTotal;
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
                setTimeout(function() {
                    $.each(settingarray, function(index, value) {
                        if (index == 'A') {
                            $.each(value, function(index, columnid) {
                                $('#datatable_ajax_approved thead').find('.' + columnid).addClass("hidecolumn");
                                $("#datatable_ajax_approved tbody").find('tr').each(function(index, value) {
                                    $(this).find('.' + columnid).addClass("hidecolumn");
                                });
                            });
                        }
                    });
                }, 1500);
            },
            onError: function(grid1) {
                // execute some code on network or other general error  
            },
            onDataLoad: function(grid1) {
                // execute some code on ajax data load
                if ($('.pagination-panel .prev').hasClass('disabled')) {
                    $("#datatable_ajax_approved tbody tr:first").find('.moveUp').hide();
                }
                if ($('.pagination-panel .next').hasClass('disabled')) {
                    $("#datatable_ajax_approved tbody tr:last").find('.moveDwn').hide();
                }

                // $('.make-switch').bootstrapSwitch();
            },
            loadingMessage: 'Loading...',
            dataTable: { // here you can define a typical datatable settings from http://datatables.net/usage/options 
                // Uncomment below line("dom" parameter) to fix the dropdown overflow issue in the datatable cells. The default datatable layout
                // setup uses scrollable div(table-scrollable) with overflow:auto to enable vertical scroll(see: assets/global/scripts/datatable.js). 
                // So when dropdowns used the scrollable div should be removed. 
                //"dom": "<'row'<'col-md-8 col-sm-12'pli><'col-md-4 col-sm-12'<'table-group-actions pull-right'>>r>t<'row'<'col-md-8 col-sm-12'pli><'col-md-4 col-sm-12'>>",
                "bStateSave": false, // save datatable state(pagination, sort, etc) in cookie.
                "lengthMenu": [
                    [10, 20, 50, 100],
                    [10, 20, 50, 100] // change per page values here
                ],
                "pageLength": 100, // default record count per page
                //Code for sorting
                "deferRender": true,
                "stateSave": true,
                "columns": [{
                        "data": 0,
                        'class': 'text-center mob-show_div',
                        'bSortable': false
                    },
                    {
                        "data": 1,
                        "name": 'varTitle',
                        className: 'text-left DecisionCategory_title_A_2 mob-show_div',
                        "bSortable": true
                    }, {
                        "data": 2,
                        "bSortable": false,
                        className: 'text-center DecisionCategory_cat_A_3'
                    },
                    //  {
                    //     "data": 3,
                    //     "name": 'dtDateTime',
                    //     className: 'text-center DecisionCategory_sdate_A_4',
                    //     "bSortable": false
                    // }, {
                    //     "data": 4,
                    //     "name": 'dtEndDateTime',
                    //     className: 'text-center DecisionCategory_edate_A_5'
                    // },
                    {
                        "data": 3,
                        "bSortable": false,
                        className: 'text-right last_td_action DecisionCategory_dactions_A_9 mob-show_div'
                    }
                ],
                "ajax": {
                    "url": window.site_url + "/powerpanel/decision-category/get_list_New", // ajax source
                },
                "order": [
                    [3, "desc"]
                ]
            }
        });
        $(document).on('change', '#statusfilter', function(e) {
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
        $(document).on('change', '#sectorfilter', function(e) {
            e.preventDefault();
            var action = $('#sectorfilter').val();

            if (action != "") {
                grid1.setAjaxParam("customActionType", "group_action");
                grid1.setAjaxParam("sectorValue", action);
                grid1.setAjaxParam("id", grid1.getSelectedRows());
                grid1.getDataTable().ajax.reload();
            } else {
                grid1.setAjaxParam("customActionType", "group_action");
                grid1.setAjaxParam("sectorValue", action);
                grid1.setAjaxParam("id", grid1.getSelectedRows());
            }
        });
        generateHeadfilterEvents(grid1);
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
                    grid1.getDataTable().ajax.reload(null, false);
                },
                error: function() {
                    console.log('error!');
                }
            });
        });
        $(document).on('keyup', '#searchfilter', function(e) {
            e.preventDefault();
            var action = $('#searchfilter').val();
            if (action.length >= 2) {
                $.cookie('ServiceCategorySearch', action);
                grid1.setAjaxParam("customActionType", "group_action");
                grid1.setAjaxParam("searchValue", action);
                grid1.setAjaxParam("id", grid1.getSelectedRows());
                grid1.getDataTable().ajax.reload();
            } else if (action.length < 1) {
                $.removeCookie('ServiceCategorySearch');
                grid1.setAjaxParam("customActionType", "group_action");
                grid1.setAjaxParam("searchValue", action);
                grid1.setAjaxParam("id", grid1.getSelectedRows());
                grid1.getDataTable().ajax.reload();
            }
        });

        $('a[data-toggle="tab"][id="MenuItem2"]').on('shown.bs.tab', function(e) {
            $("#hidefilter").show();
            e.preventDefault();
            grid1.setAjaxParam("id", grid1.getSelectedRows());
            grid1.getDataTable().ajax.reload();
        });
        grid1.setAjaxParam("customActionType", "group_action");
        grid1.clearAjaxParams();
        grid1.getDataTable().columns().iterator('column', function(ctx, idx) {
            $(grid1.getDataTable().column(idx).header()).append('<span class="sort-icon"/>');
        });
    }
    return {
        //main function to initiate the module
        init: function() {
            initPickers();
            handleRecords();
        }
    };
}();



var gridRows2 = 0;
var grid2;
var TableDatatablesAjax2 = function() {
    var initPickers2 = function() {}
    var handleRecords2 = function() {
        var action = $('#category_id').val();
        grid2 = new Datatable();
        grid2.setAjaxParam("catValue", action);
        grid2.init({
            src: $("#datatable_ajax2"),
            onSuccess: function(grid2, response) {
                gridRows2 = response.recordsTotal;
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
                setTimeout(function() {
                    $.each(settingarray, function(index, value) {
                        if (index == 'D') {
                            $.each(value, function(index, columnid) {
                                $('#datatable_ajax2 thead').find('.' + columnid).addClass("hidecolumn");
                                $("#datatable_ajax2 tbody").find('tr').each(function(index, value) {
                                    $(this).find('.' + columnid).addClass("hidecolumn");
                                });
                            });
                        }
                    });
                }, 1500);
            },
            onError: function(grid2) {
                // execute some code on network or other general error  
            },
            onDataLoad: function(grid2) {
                // execute some code on ajax data load
                if ($('.pagination-panel .prev').hasClass('disabled')) {
                    $("#datatable_ajax2 tbody tr:first").find('.moveUp').hide();
                }
                if ($('.pagination-panel .next').hasClass('disabled')) {
                    $("#datatable_ajax2 tbody tr:last").find('.moveDwn').hide();
                }

                // $('.make-switch').bootstrapSwitch();
            },
            loadingMessage: 'Loading...',
            dataTable: { // here you can define a typical datatable settings from http://datatables.net/usage/options 
                // Uncomment below line("dom" parameter) to fix the dropdown overflow issue in the datatable cells. The default datatable layout
                // setup uses scrollable div(table-scrollable) with overflow:auto to enable vertical scroll(see: assets/global/scripts/datatable.js). 
                // So when dropdowns used the scrollable div should be removed. 
                //"dom": "<'row'<'col-md-8 col-sm-12'pli><'col-md-4 col-sm-12'<'table-group-actions pull-right'>>r>t<'row'<'col-md-8 col-sm-12'pli><'col-md-4 col-sm-12'>>",
                "bStateSave": false, // save datatable state(pagination, sort, etc) in cookie.
                "lengthMenu": [
                    [10, 20, 50, 100],
                    [10, 20, 50, 100] // change per page values here
                ],
                "pageLength": 100, // default record count per page
                //Code for sorting
                "deferRender": true,
                "stateSave": true,
                "columns": [{
                        "data": 0,
                        "bSortable": false,
                        'class': 'text-center mob-show_div',
                    }, {
                        "data": 1,
                        "name": 'varTitle',
                        className: 'text-left DecisionCategory_title_D_2 mob-show_div',
                        "bSortable": false
                    }, {
                        "data": 2,
                        "bSortable": false,
                        className: 'text-center DecisionCategory_cat_D_3'
                    },
                    //  {
                    //     "data": 3,
                    //     className: 'text-center DecisionCategory_sdate_D_4',
                    //     "name": 'dtDateTime',
                    //     "bSortable": true
                    // }, {
                    //     "data": 4,
                    //     className: 'text-center DecisionCategory_edate_D_5',
                    //     "name": 'dtEndDateTime',
                    //     "bSortable": true
                    // },
                    {
                        "data": 3,
                        "bSortable": false,
                        className: 'text-center form-switch DecisionCategory_publish_D_8'
                    },
                    {
                        "data": 4,
                        "bSortable": false,
                        className: 'text-right last_td_action DecisionCategory_dactions_D_9 mob-show_div'
                    },
                    {
                        "data": 5,
                        className: 'text-center'
                    }
                ],
                "columnDefs": [{
                    "targets": [5],
                    "visible": false,
                    "searchable": false
                }],
                "ajax": {
                    "url": window.site_url + "/powerpanel/decision-category/get_list_draft", // ajax source
                },
                'fnCreatedRow': function(nRow, aData, iDataIndex) {
                    $(nRow).attr('data-order', aData[5]);
                },
                "order": [
                    [3, "desc"]
                ]
            }
        });
        $('#datatable_ajax2 tbody').on('click', '.moveDwn', function() {
            var order = $(this).data('order');
            var exOrder = order + 1;
            var parentRecordId = $(this).attr('data-parentRecordId');
            reorder(order, exOrder, parentRecordId);
        });
        $('#datatable_ajax2 tbody').on('click', '.moveUp', function() {
            var order = $(this).data('order');
            var exOrder = order - 1;
            exOrder = (exOrder == 0) ? 1 : exOrder;
            var parentRecordId = $(this).attr('data-parentRecordId');
            reorder(order, exOrder, parentRecordId);
        });
        generateHeadfilterEvents(grid2);
        $(document).on('change', '#statusfilter', function(e) {
            e.preventDefault();
            var action = $('#statusfilter').val();
            if (action != " ") {
                //$.cookie('ServiceCategoryStatus',action);
            } else {
                //$.removeCookie('ServiceCategoryStatus');
            }
            if (action != "") {
                grid2.setAjaxParam("customActionType", "group_action");
                grid2.setAjaxParam("statusValue", action);
                grid2.setAjaxParam("id", grid2.getSelectedRows());
                grid2.getDataTable().ajax.reload();
            } else {
                grid2.setAjaxParam("customActionType", "group_action");
                grid2.setAjaxParam("statusValue", action);
                grid2.setAjaxParam("id", grid2.getSelectedRows());
            }
        });
        $(document).on('change', '#sectorfilter', function(e) {
            e.preventDefault();
            var action = $('#sectorfilter').val();

            if (action != "") {
                grid2.setAjaxParam("customActionType", "group_action");
                grid2.setAjaxParam("sectorValue", action);
                grid2.setAjaxParam("id", grid2.getSelectedRows());
                grid2.getDataTable().ajax.reload();
            } else {
                grid2.setAjaxParam("customActionType", "group_action");
                grid2.setAjaxParam("sectorValue", action);
                grid2.setAjaxParam("id", grid2.getSelectedRows());
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
                    grid2.getDataTable().ajax.reload(null, false);
                },
                error: function() {
                    console.log('error!');
                }
            });
        });
        $(document).on('keyup', '#searchfilter', function(e) {
            e.preventDefault();
            var action = $('#searchfilter').val();
            if (action.length >= 2) {
                $.cookie('ServiceCategorySearch', action);
                grid2.setAjaxParam("customActionType", "group_action");
                grid2.setAjaxParam("searchValue", action);
                grid2.setAjaxParam("id", grid2.getSelectedRows());
                grid2.getDataTable().ajax.reload();
            } else if (action.length < 1) {
                $.removeCookie('ServiceCategorySearch');
                grid2.setAjaxParam("customActionType", "group_action");
                grid2.setAjaxParam("searchValue", action);
                grid2.setAjaxParam("id", grid2.getSelectedRows());
                grid2.getDataTable().ajax.reload();
            }
        });

        $('a[data-toggle="tab"][id="MenuItem2"]').on('shown.bs.tab', function(e) {
            $("#hidefilter").hide();
            e.preventDefault();
            grid2.setAjaxParam("id", grid2.getSelectedRows());
            grid2.getDataTable().ajax.reload();
        });

        $(document).on("click", '#DgrpChkBox .checkbox_D', function() {
            if ($(this).prop("checked") == true) {
                var datatable_D = $(this).attr("name").split("_");
                grid2.getDataTable().column(datatable_D[3]).visible(true);
                Cookies.set($(this).attr("name"), 'Y');
                grid2.getDataTable().ajax.reload();
            } else {
                var datatable_D = $(this).attr("name").split("_");
                grid2.getDataTable().column(datatable_D[3]).visible(false);
                Cookies.set($(this).attr("name"), 'N');
                grid2.getDataTable().ajax.reload();
            }
        });

        grid2.setAjaxParam("customActionType", "group_action");
        grid2.clearAjaxParams();
        grid2.getDataTable().columns().iterator('column', function(ctx, idx) {
            $(grid2.getDataTable().column(idx).header()).append('<span class="sort-icon"/>');
        });
    }
    return {
        //main function to initiate the module
        init: function() {
            initPickers2();
            handleRecords2();
        }
    };
}();


var gridRows3 = 0;
var grid3;
var TableDatatablesAjax3 = function() {
    var initPickers3 = function() {}
    var handleRecords3 = function() {
        var action = $('#category_id').val();
        grid3 = new Datatable();
        grid3.setAjaxParam("catValue", action);
        grid3.init({
            src: $("#datatable_ajax3"),
            onSuccess: function(grid3, response) {
                gridRows3 = response.recordsTotal;
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
                setTimeout(function() {
                    $.each(settingarray, function(index, value) {
                        if (index == 'T') {
                            $.each(value, function(index, columnid) {
                                $('#datatable_ajax3 thead').find('.' + columnid).addClass("hidecolumn");
                                $("#datatable_ajax3 tbody").find('tr').each(function(index, value) {
                                    $(this).find('.' + columnid).addClass("hidecolumn");
                                });
                            });
                        }
                    });
                }, 1500);
            },
            onError: function(grid3) {
                // execute some code on network or other general error  
            },
            onDataLoad: function(grid3) {
                // execute some code on ajax data load
                if ($('.pagination-panel .prev').hasClass('disabled')) {
                    $("#datatable_ajax3 tbody tr:first").find('.moveUp').hide();
                }
                if ($('.pagination-panel .next').hasClass('disabled')) {
                    $("#datatable_ajax3 tbody tr:last").find('.moveDwn').hide();
                }

                // $('.make-switch').bootstrapSwitch();
            },
            loadingMessage: 'Loading...',
            dataTable: { // here you can define a typical datatable settings from http://datatables.net/usage/options 
                // Uncomment below line("dom" parameter) to fix the dropdown overflow issue in the datatable cells. The default datatable layout
                // setup uses scrollable div(table-scrollable) with overflow:auto to enable vertical scroll(see: assets/global/scripts/datatable.js). 
                // So when dropdowns used the scrollable div should be removed. 
                //"dom": "<'row'<'col-md-8 col-sm-12'pli><'col-md-4 col-sm-12'<'table-group-actions pull-right'>>r>t<'row'<'col-md-8 col-sm-12'pli><'col-md-4 col-sm-12'>>",
                "bStateSave": false, // save datatable state(pagination, sort, etc) in cookie.
                "lengthMenu": [
                    [10, 20, 50, 100],
                    [10, 20, 50, 100] // change per page values here
                ],
                "pageLength": 100, // default record count per page
                //Code for sorting
                "deferRender": true,
                "stateSave": true,
                "columns": [{
                        "data": 0,
                        "bSortable": false,
                        'class': 'text-center mob-show_div',
                    }, {
                        "data": 1,
                        "name": 'varTitle',
                        className: 'text-left DecisionCategory_title_T_2 mob-show_div',
                        "bSortable": false
                    }, {
                        "data": 2,
                        "bSortable": false,
                        className: 'text-center DecisionCategory_cat_T_3'
                    },
                    //  {
                    //     "data": 3,
                    //     className: 'text-center DecisionCategory_sdate_T_4',
                    //     "name": 'dtDateTime',
                    //     "bSortable": true
                    // }, {
                    //     "data": 4,
                    //     className: 'text-center DecisionCategory_edate_T_5',
                    //     "name": 'dtEndDateTime',
                    //     "bSortable": true
                    // },
                    {
                        "data": 3,
                        "bSortable": false,
                        className: 'text-right last_td_action DecisionCategory_dactions_T_9 mob-show_div'
                    },
                    {
                        "data": 4,
                        className: 'text-center'
                    }
                ],
                "columnDefs": [{
                    "targets": [4],
                    "visible": false,
                    "searchable": false
                }],
                "ajax": {
                    "url": window.site_url + "/powerpanel/decision-category/get_list_trash", // ajax source
                },
                'fnCreatedRow': function(nRow, aData, iDataIndex) {
                    $(nRow).attr('data-order', aData[3]);
                },
                "order": [
                    [3, "desc"]
                ]
            }
        });
        $('#datatable_ajax3 tbody').on('click', '.moveDwn', function() {
            var order = $(this).data('order');
            var exOrder = order + 1;
            var parentRecordId = $(this).attr('data-parentRecordId');
            reorder(order, exOrder, parentRecordId);
        });
        $('#datatable_ajax3 tbody').on('click', '.moveUp', function() {
            var order = $(this).data('order');
            var exOrder = order - 1;
            exOrder = (exOrder == 0) ? 1 : exOrder;
            var parentRecordId = $(this).attr('data-parentRecordId');
            reorder(order, exOrder, parentRecordId);
        });
        generateHeadfilterEvents(grid3);
        $(document).on('change', '#statusfilter', function(e) {
            e.preventDefault();
            var action = $('#statusfilter').val();
            if (action != " ") {
                //$.cookie('ServiceCategoryStatus',action);
            } else {
                //$.removeCookie('ServiceCategoryStatus');
            }
            if (action != "") {
                grid3.setAjaxParam("customActionType", "group_action");
                grid3.setAjaxParam("statusValue", action);
                grid3.setAjaxParam("id", grid3.getSelectedRows());
                grid3.getDataTable().ajax.reload();
            } else {
                grid3.setAjaxParam("customActionType", "group_action");
                grid3.setAjaxParam("statusValue", action);
                grid3.setAjaxParam("id", grid3.getSelectedRows());
            }
        });
        $(document).on('change', '#sectorfilter', function(e) {
            e.preventDefault();
            var action = $('#sectorfilter').val();

            if (action != "") {
                grid3.setAjaxParam("customActionType", "group_action");
                grid3.setAjaxParam("sectorValue", action);
                grid3.setAjaxParam("id", grid3.getSelectedRows());
                grid3.getDataTable().ajax.reload();
            } else {
                grid3.setAjaxParam("customActionType", "group_action");
                grid3.setAjaxParam("sectorValue", action);
                grid3.setAjaxParam("id", grid3.getSelectedRows());
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
                    grid3.getDataTable().ajax.reload(null, false);
                },
                error: function() {
                    console.log('error!');
                }
            });
        });
        $(document).on('keyup', '#searchfilter', function(e) {
            e.preventDefault();
            var action = $('#searchfilter').val();
            if (action.length >= 2) {
                $.cookie('ServiceCategorySearch', action);
                grid3.setAjaxParam("customActionType", "group_action");
                grid3.setAjaxParam("searchValue", action);
                grid3.setAjaxParam("id", grid3.getSelectedRows());
                grid3.getDataTable().ajax.reload();
            } else if (action.length < 1) {
                $.removeCookie('ServiceCategorySearch');
                grid3.setAjaxParam("customActionType", "group_action");
                grid3.setAjaxParam("searchValue", action);
                grid3.setAjaxParam("id", grid3.getSelectedRows());
                grid3.getDataTable().ajax.reload();
            }
        });

        $('a[data-toggle="tab"][id="MenuItem3"]').on('shown.bs.tab', function(e) {
            $("#hidefilter").hide();
            e.preventDefault();
            grid3.setAjaxParam("id", grid3.getSelectedRows());
            grid3.getDataTable().ajax.reload();
        });

        grid3.setAjaxParam("customActionType", "group_action");
        grid3.clearAjaxParams();
        grid3.getDataTable().columns().iterator('column', function(ctx, idx) {
            $(grid3.getDataTable().column(idx).header()).append('<span class="sort-icon"/>');
        });
    }
    return {
        //main function to initiate the module
        init: function() {
            initPickers3();
            handleRecords3();
        }
    };
}();


var gridRows4 = 0;
var grid4;
var TableDatatablesAjax4 = function() {
    var initPickers4 = function() {}
    var handleRecords4 = function() {
        var action = $('#category_id').val();
        grid4 = new Datatable();
        grid4.setAjaxParam("catValue", action);
        grid4.init({
            src: $("#datatable_ajax4"),
            onSuccess: function(grid4, response) {
                gridRows4 = response.recordsTotal;
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
                setTimeout(function() {
                    $.each(settingarray, function(index, value) {
                        if (index == 'F') {
                            $.each(value, function(index, columnid) {
                                $('#datatable_ajax4 thead').find('.' + columnid).addClass("hidecolumn");
                                $("#datatable_ajax4 tbody").find('tr').each(function(index, value) {
                                    $(this).find('.' + columnid).addClass("hidecolumn");
                                });
                            });
                        }
                    });
                }, 1500);
            },
            onError: function(grid4) {
                // execute some code on network or other general error  
            },
            onDataLoad: function(grid4) {
                // execute some code on ajax data load
                if ($('.pagination-panel .prev').hasClass('disabled')) {
                    $("#datatable_ajax4 tbody tr:first").find('.moveUp').hide();
                }
                if ($('.pagination-panel .next').hasClass('disabled')) {
                    $("#datatable_ajax4 tbody tr:last").find('.moveDwn').hide();
                }

                // $('.make-switch').bootstrapSwitch();
            },
            loadingMessage: 'Loading...',
            dataTable: { // here you can define a typical datatable settings from http://datatables.net/usage/options 
                // Uncomment below line("dom" parameter) to fix the dropdown overflow issue in the datatable cells. The default datatable layout
                // setup uses scrollable div(table-scrollable) with overflow:auto to enable vertical scroll(see: assets/global/scripts/datatable.js). 
                // So when dropdowns used the scrollable div should be removed. 
                //"dom": "<'row'<'col-md-8 col-sm-12'pli><'col-md-4 col-sm-12'<'table-group-actions pull-right'>>r>t<'row'<'col-md-8 col-sm-12'pli><'col-md-4 col-sm-12'>>",
                "bStateSave": false, // save datatable state(pagination, sort, etc) in cookie.
                "lengthMenu": [
                    [10, 20, 50, 100],
                    [10, 20, 50, 100] // change per page values here
                ],
                "pageLength": 100, // default record count per page
                //Code for sorting
                "deferRender": true,
                "stateSave": true,
                "columns": [{
                        "data": 0,
                        "bSortable": false,
                        className: 'text-center',
                    }, {
                        "data": 1,
                        'class': 'text-center mob-show_div',
                        'bSortable': false
                    }, {
                        "data": 2,
                        "name": 'varTitle',
                        className: 'text-left DecisionCategory_title_F_2 mob-show_div',
                        "bSortable": false
                    }, {
                        "data": 3,
                        "bSortable": false,
                        className: 'text-center DecisionCategory_cat_F_3'
                    },
                    //  {
                    //     "data": 4,
                    //     className: 'text-center DecisionCategory_sdate_F_4',
                    //     "name": 'dtDateTime',
                    //     "bSortable": true
                    // }, {
                    //     "data": 5,
                    //     className: 'text-center DecisionCategory_edate_F_5',
                    //     "name": 'dtEndDateTime',
                    //     "bSortable": true
                    // },
                    {
                        "data": 4,
                        "bSortable": false,
                        className: 'text-right last_td_action DecisionCategory_dactions_F_9 mob-show_div'
                    },
                    {
                        "data": 5,
                        className: 'text-center'
                    }
                ],
                "columnDefs": [{
                    "targets": [5],
                    "visible": false,
                    "searchable": false
                }],
                "ajax": {
                    "url": window.site_url + "/powerpanel/decision-category/get_list_favorite", // ajax source
                },
                'fnCreatedRow': function(nRow, aData, iDataIndex) {
                    $(nRow).attr('data-order', aData[4]);
                },
                "order": [
                    [4, "desc"]
                ]
            }
        });
        $('#datatable_ajax4 tbody').on('click', '.moveDwn', function() {
            var order = $(this).data('order');
            var exOrder = order + 1;
            var parentRecordId = $(this).attr('data-parentRecordId');
            reorder(order, exOrder, parentRecordId);
        });
        $('#datatable_ajax4 tbody').on('click', '.moveUp', function() {
            var order = $(this).data('order');
            var exOrder = order - 1;
            exOrder = (exOrder == 0) ? 1 : exOrder;
            var parentRecordId = $(this).attr('data-parentRecordId');
            reorder(order, exOrder, parentRecordId);
        });
        generateHeadfilterEvents(grid4);
        $(document).on('change', '#statusfilter', function(e) {
            e.preventDefault();
            var action = $('#statusfilter').val();
            if (action != " ") {
                //$.cookie('ServiceCategoryStatus',action);
            } else {
                //$.removeCookie('ServiceCategoryStatus');
            }
            if (action != "") {
                grid4.setAjaxParam("customActionType", "group_action");
                grid4.setAjaxParam("statusValue", action);
                grid4.setAjaxParam("id", grid4.getSelectedRows());
                grid4.getDataTable().ajax.reload();
            } else {
                grid4.setAjaxParam("customActionType", "group_action");
                grid4.setAjaxParam("statusValue", action);
                grid4.setAjaxParam("id", grid4.getSelectedRows());
            }
        });
        $(document).on('change', '#sectorfilter', function(e) {
            e.preventDefault();
            var action = $('#sectorfilter').val();

            if (action != "") {
                grid4.setAjaxParam("customActionType", "group_action");
                grid4.setAjaxParam("sectorValue", action);
                grid4.setAjaxParam("id", grid4.getSelectedRows());
                grid4.getDataTable().ajax.reload();
            } else {
                grid4.setAjaxParam("customActionType", "group_action");
                grid4.setAjaxParam("sectorValue", action);
                grid4.setAjaxParam("id", grid4.getSelectedRows());
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
                    grid4.getDataTable().ajax.reload(null, false);
                },
                error: function() {
                    console.log('error!');
                }
            });
        });
        $(document).on('keyup', '#searchfilter', function(e) {
            e.preventDefault();
            var action = $('#searchfilter').val();
            if (action.length >= 2) {
                $.cookie('ServiceCategorySearch', action);
                grid4.setAjaxParam("customActionType", "group_action");
                grid4.setAjaxParam("searchValue", action);
                grid4.setAjaxParam("id", grid4.getSelectedRows());
                grid4.getDataTable().ajax.reload();
            } else if (action.length < 1) {
                $.removeCookie('ServiceCategorySearch');
                grid4.setAjaxParam("customActionType", "group_action");
                grid4.setAjaxParam("searchValue", action);
                grid4.setAjaxParam("id", grid4.getSelectedRows());
                grid4.getDataTable().ajax.reload();
            }
        });

        $('a[data-toggle="tab"][id="MenuItem4"]').on('shown.bs.tab', function(e) {
            $("#hidefilter").hide();
            e.preventDefault();
            grid4.setAjaxParam("id", grid4.getSelectedRows());
            grid4.getDataTable().ajax.reload();
        });

        grid4.setAjaxParam("customActionType", "group_action");
        grid4.clearAjaxParams();
        grid4.getDataTable().columns().iterator('column', function(ctx, idx) {
            $(grid4.getDataTable().column(idx).header()).append('<span class="sort-icon"/>');
        });
    }
    return {
        //main function to initiate the module
        init: function() {
            initPickers4();
            handleRecords4();
        }
    };
}();
$(document).ready(function() {
    let cookie = clearcookie;
    if (cookie == 'true') {
        $.removeCookie('ServiceCategorySearch');
        $('#searchfilter').val('');
    }
});


$(window).on('load', function() {
    if ($.cookie('ServiceCategorySearch')) {
        $('#searchfilter').val($.cookie('ServiceCategorySearch'));
        $('#searchfilter').trigger('keyup');
    }
});
jQuery(document).ready(function() {
    $.ajaxSetup({
        headers: {
            'X-CSRF-Token': $('input[name="_token"]').val()
        }
    });
    $("#hidefilter").show();
    TableDatatablesAjax.init();
    //TableDatatablesAjaxApproval.init();
    if (!showChecker) {
        grid.getDataTable().column(5).visible(false);
    } else {
        grid.getDataTable().column(5).visible(true);
    }
    grid.getDataTable().column(6).visible(true);
    Custom.init();
     $('#sectorfilter').on("change", function(e) {
         Custom.getModuleRecords($("#sectorfilter option:selected").val());
    });
});

var Custom = function() {
    return {
        //main function
        init: function() {
            //initialize here something.            
        },

        getModuleRecords: function(sectorName) {

            var ajaxUrl = site_url + '/powerpanel/decision-category/getSectorwiseCategoryGrid';
            jQuery.ajax({
                type: "POST",
                url: ajaxUrl,
                dataType: 'HTML',
                data: { "sectorname": sectorName},
                async: false,
                success: function(result) {
                    $("#categoriesfilter").html(result).trigger('change.select2');
                }
            });
        }
    }
}();

$('a[data-toggle="tab"][id="MenuItem2"]').on('shown.bs.tab', function(e) {
    $("#hidefilter").show();
    if (!$.fn.DataTable.isDataTable('#decisioncat_datatable_approval_ajax')) {
        TableDatatablesAjaxApproval.init();
    }
});

$('a[data-toggle="tab"][id="MenuItem3"]').on('shown.bs.tab', function(e) {
    $("#hidefilter").hide();
    if (!$.fn.DataTable.isDataTable('#datatable_ajax2')) {
        TableDatatablesAjax2.init();
        if (!showChecker) {
            grid2.getDataTable().column(0).visible(false);
        } else {
            grid2.getDataTable().column(0).visible(true);
        }
    }
});

$('a[data-toggle="tab"][id="MenuItem4"]').on('shown.bs.tab', function(e) {
    $("#hidefilter").hide();
    if (!$.fn.DataTable.isDataTable('#datatable_ajax3')) {
        TableDatatablesAjax3.init();
    }
});

$('a[data-toggle="tab"][id="MenuItem5"]').on('shown.bs.tab', function(e) {
    $("#hidefilter").hide();
    if (!$.fn.DataTable.isDataTable('#datatable_ajax4')) {
        TableDatatablesAjax4.init();
        if (!showChecker) {
            grid4.getDataTable().column(0).visible(false);
        } else {
            grid4.getDataTable().column(0).visible(true);
        }
    }
});

function generateHeadfilterEvents(gridvariable) {
    $(document).off('.list_head_filter').on('click', '.list_head_filter', function(e) {
        e.preventDefault();
        var action = $(this).attr("data-filterIdentity");
        if (action != "") {
            gridvariable.setAjaxParam("customActionType", "group_action");
            gridvariable.setAjaxParam("customFilterIdentity", action);
            gridvariable.setAjaxParam("id", gridvariable.getSelectedRows());
            gridvariable.getDataTable().ajax.reload();
        } else {
            gridvariable.setAjaxParam("customActionType", "group_action");
            gridvariable.setAjaxParam("customFilterIdentity", "");
            gridvariable.setAjaxParam("id", gridvariable.getSelectedRows());
        }
    });
    $(document).on('change', '.list_head_filter', function(e) {
        gridvariable.setAjaxParam("customFilterIdentity", "");
    });
    $('.list_head_filter').trigger('change');
}

function reorder(curOrder, excOrder, parentRecordId) {
    var ajaxurl = site_url + '/powerpanel/decision-category/reorder';
    $.ajax({
        url: ajaxurl,
        data: {
            order: curOrder,
            exOrder: excOrder,
            parentRecordId: parentRecordId
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