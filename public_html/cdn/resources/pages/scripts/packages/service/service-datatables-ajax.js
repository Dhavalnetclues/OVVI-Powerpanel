var gridRows = 0;   // All
var grid = '';
var TableDatatablesAjax = function() {
    var handleRecords = function() {

        grid = new Datatable();
        grid.init({
            src: $("#datatable_ajax"),
            onSuccess: function(grid, response) {
                gridRows = response.recordsTotal;
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
            onError: function(grid) {},
            onDataLoad: function(grid) {
            	
            },
            loadingMessage: 'Loading...',
            dataTable: { // here you can define a typical datatable settings from http://datatables.net/usage/options
                // Uncomment below line("dom" parameter) to fix the dropdown overflow issue in the datatable cells. The default datatable layout
                // setup uses scrollable div(table-scrollable) with overflow:auto to enable vertical scroll(see: assets/global/scripts/datatable.js).
                // So when dropdowns used the scrollable div should be removed.
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
                    'class': 'text-left Pages_title_P_1 mob-show_div',
                    'name': 'varTitle'
                }, {
                    "data": 2,
                    "bSortable": false,
                    'class': 'text-center Services_cat_P_3'
                }, {
                    "data": 3,
                    "name": 'serviceCode',
                    "class": 'text-center Services_sdate_P_3'
                }, {
                    "data": 4,
                    "class": 'text-center Services_order_P_4',
                    "name": 'intDisplayOrder'
                },{
                    "data": 5,
                    'class': 'text-center form-switch Pages_publish_P_3',
                    'name': 'publish',
                    'bSortable': false
                }, {
                    "data": 6,
                    'class': 'text-center Pages_dactions_P_4 last_td_action mob-show_div',
                    'bSortable': false
                } ],
                "ajax": {
                    "url": window.site_url + "/powerpanel/service/get_list", // ajax source
                },
                'fnCreatedRow': function(nRow, aData, iDataIndex) {
                    // $(nRow).attr('data-order', aData[7]);
                },
                "order": [
                    [4, "asc"]
                ]
            }
        });

        $('#datatable_ajax tbody').on('click', '.moveDwn', function() {
            var order = $(this).data('order');
            exOrder = order + 1;
            reorder(order, exOrder);
        });
        $('#datatable_ajax tbody').on('click', '.moveUp', function() {
            var order = $(this).data('order');
            exOrder = order - 1;
            reorder(order, exOrder);
        });

        generateHeadfilterEvents(grid);
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
                $.cookie('Newssearch', action);
                grid.setAjaxParam("customActionType", "group_action");
                grid.setAjaxParam("searchValue", action);
                grid.setAjaxParam("id", grid.getSelectedRows());
                grid.getDataTable().ajax.reload();
            } else if (action.length < 1) {
                $.removeCookie('Newssearch');
                grid.setAjaxParam("customActionType", "group_action");
                grid.setAjaxParam("searchValue", action);
                grid.setAjaxParam("id", grid.getSelectedRows());
                grid.getDataTable().ajax.reload();
            }
        });

        $(document).on('click', '#newsRange', function(e) {
            e.preventDefault();
            var action = {};
            action['from'] = $('#start_date').val();
            action['to'] = $('#end_date').val();
            if (action['from'] != '' && action['to'] != '') {
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

        $('a[data-toggle="tab"][id="MenuItem1"]').on('shown.bs.tab', function(e) {
            $("#hidefilter").show();
            e.preventDefault();
            grid.setAjaxParam("id", grid.getSelectedRows());
            grid.getDataTable().ajax.reload();
        });

        // if (!showChecker) {
        //     grid.getDataTable().column(6).visible(false);
        // } else {
        //     grid.getDataTable().column(6).visible(true);
        // }
        // grid.getDataTable().column(7).visible(true);

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
            handleRecords();
        }
    };
}();


var grid1Rows = 0;  // Approval
var grid1 = '';
var TableDatatablesAjax1 = function() {
    var handleRecords1 = function() {

        grid1 = new Datatable();
        grid1.init({
            src: $("#datatable_ajax_approved"),
            onSuccess: function(grid1, response) {
                grid1Rows = response.recordsTotal;
                if (response.recordsTotal < 1) {
                    $('.deleteMass').hide();
                    $("#menu2.tab-pane .notabreocrd").show();
										$("#menu2.tab-pane .withrecords").hide();
                } else {
                    $('.deleteMass').show();
                    $("#menu2.tab-pane .notabreocrd").hide();
										$("#menu2.tab-pane .withrecords").show();
                }
                if(response.recordsTotal < 20) {
                    $('.gridjs-pages').hide();
                } else {
                    $('.gridjs-pages').show();
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
            onError: function(grid1) {},
            onDataLoad: function(grid1) {
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
                "dom": "t <'gridjs-footer' <'gridjs-pagination'i <'gridjs-pages'p>>>",
                "deferRender": true,
                // "stateSave": true, // save datatable state(pagination, sort, etc) in cookie.
                // "lengthMenu": [
                //     [10, 20, 50, 100],
                //     [10, 20, 50, 100] // change per page values here
                // ],
                "pageLength": 20, // default record count per page
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
                    'class': 'text-left Pages_title_P_1 mob-show_div',
                    'name': 'varTitle'
                }, {
                    "data": 2,
                    "bSortable": false,
                    'class': 'text-center Services_cat_P_3'
                }, {
                    "data": 3,
                    "name": 'serviceCode',
                    "class": 'text-center Services_sdate_P_3'
                }, {
                    "data": 4,
                    'class': 'text-center form-switch Pages_publish_P_3',
                    'name': 'publish',
                    'bSortable': false
                }, {
                    "data": 5,
                    'class': 'text-center Pages_dactions_P_4 last_td_action mob-show_div',
                    'bSortable': false
                } ],
                "ajax": {
                    "url": window.site_url + "/powerpanel/service/get_list_New", // ajax source
                },
                'fnCreatedRow': function(nRow, aData, iDataIndex) {
                    // $(nRow).attr('data-order', aData[4]);
                },
                "order": [
                    [1, "desc"]
                ]
            }
        });

        $('#datatable_ajax_approved tbody').on('click', '.moveDwn', function() {
            var order = $(this).data('order');
            var exOrder = $('#datatable_ajax1 tbody').find('tr[data-order=' + order + ']').next().data('order');
            exOrder = (exOrder == undefined) ? order + 1 : exOrder;
            reorder(order, exOrder);
        });
        $('#datatable_ajax_approved tbody').on('click', '.moveUp', function() {
            var order = $(this).data('order');
            var exOrder = $('#datatable_ajax1 tbody').find('tr[data-order=' + order + ']').prev().data('order');
            exOrder = (exOrder == undefined) ? order - 1 : exOrder;
            reorder(order, exOrder);
        });

        $(document).on('change', '#statusfilter', function(e) {
            e.preventDefault();
            var action = $('#statusfilter').val();
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
                $.cookie('Newssearch', action);
                grid1.setAjaxParam("customActionType", "group_action");
                grid1.setAjaxParam("searchValue", action);
                grid1.setAjaxParam("id", grid1.getSelectedRows());
                grid1.getDataTable().ajax.reload();
            } else if (action.length < 1) {
                $.removeCookie('Newssearch');
                grid1.setAjaxParam("customActionType", "group_action");
                grid1.setAjaxParam("searchValue", action);
                grid1.setAjaxParam("id", grid1.getSelectedRows());
                grid1.getDataTable().ajax.reload();
            }
        });

        $(document).on('click', '#newsRange', function(e) {
            e.preventDefault();
            var action = {};
            action['from'] = $('#start_date').val();
            action['to'] = $('#end_date').val();
            if (action['from'] != '' && action['to'] != '') {
                grid1.setAjaxParam("customActionType", "group_action");
                grid1.setAjaxParam("rangeFilter", action);
                grid1.setAjaxParam("id", grid1.getSelectedRows());
                grid1.getDataTable().ajax.reload();
            } else {
                grid1.setAjaxParam("customActionType", "group_action");
                grid1.setAjaxParam("rangeFilter", action);
                grid1.setAjaxParam("id", grid1.getSelectedRows());
            }
        });

        $(document).on('click', '#refresh', function(e) {
            $('#start_date').val('');
            $('#end_date').val('');
            grid1.setAjaxParam("rangeFilter", '');
            grid1.getDataTable().ajax.reload();
        });

        $('a[data-toggle="tab"][id="MenuItem1"]').on('shown.bs.tab', function(e) {
            e.preventDefault();
            grid.setAjaxParam("id", grid.getSelectedRows());
            grid.getDataTable().ajax.reload();
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
            $.fn.DataTable.ext.pager.numbers_length = 4;
            handleRecords1();
        }
    };
}();


var gridRows2 = 0;  //  Draft
var grid2 = '';
var TableDatatablesAjax2 = function() {
    var handleRecords2 = function() {

        grid2 = new Datatable();
        grid2.init({
            src: $("#datatable_ajax2"),
            onSuccess: function(grid2, response) {
                gridRows2 = response.recordsTotal;
                if (response.recordsTotal < 1) {
                    $('.deleteMass').hide();
                    $("#menu3.tab-pane .notabreocrd").show();
										$("#menu3.tab-pane .withrecords").hide();
                } else {
                    $('.deleteMass').show();
                    $("#menu3.tab-pane .notabreocrd").hide();
										$("#menu3.tab-pane .withrecords").show();
                }
                if(response.recordsTotal < 20) {
                    $('.gridjs-pages').hide();
                } else {
                    $('.gridjs-pages').show();
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
            onError: function(grid2) {},
            onDataLoad: function(grid2) {
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
                "dom": "t <'gridjs-footer' <'gridjs-pagination'i <'gridjs-pages'p>>>",
                "deferRender": true,
                // "stateSave": true, // save datatable state(pagination, sort, etc) in cookie.
                // "lengthMenu": [
                //     [10, 20, 50, 100],
                //     [10, 20, 50, 100] // change per page values here
                // ],
                "pageLength": 20, // default record count per page
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
                    'class': 'text-left Pages_title_P_1 mob-show_div',
                    'name': 'varTitle'
                }, {
                    "data": 2,
                    "bSortable": false,
                    'class': 'text-center Services_cat_P_3'
                }, {
                    "data": 3,
                    "name": 'serviceCode',
                    "class": 'text-center Services_sdate_P_3'
                }, {
                    "data": 4,
                    'class': 'text-center form-switch Pages_publish_P_3',
                    'name': 'publish',
                    'bSortable': false
                }, {
                    "data": 5,
                    'class': 'text-center Pages_dactions_P_4 last_td_action mob-show_div',
                    'bSortable': false
                } ],
                "ajax": {
                    "url": window.site_url + "/powerpanel/service/get_list_draft", // ajax source
                },
                'fnCreatedRow': function(nRow, aData, iDataIndex) {
                    // $(nRow).attr('data-order', aData[5]);
                },
                "order": [
                    [1, "desc"]
                ]
            }
        });

        $('#datatable_ajax2 tbody').on('click', '.moveDwn', function() {
            var order = $(this).data('order');
            var exOrder = $('#datatable_ajax2 tbody').find('tr[data-order=' + order + ']').next().data('order');
            exOrder = (exOrder == undefined) ? order + 1 : exOrder;
            reorder(order, exOrder);
        });
        $('#datatable_ajax2 tbody').on('click', '.moveUp', function() {
            var order = $(this).data('order');
            var exOrder = $('#datatable_ajax2 tbody').find('tr[data-order=' + order + ']').prev().data('order');
            exOrder = (exOrder == undefined) ? order - 1 : exOrder;
            reorder(order, exOrder);
        });

        generateHeadfilterEvents(grid2);
        $(document).on('change', '#statusfilter', function(e) {
            e.preventDefault();
            var action = $('#statusfilter').val();
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

        $(document).on('change', '#category_id', function(e) {
            e.preventDefault();
            var action = $('#category_id').val();
            if (action != "") {
                grid2.setAjaxParam("customActionType", "group_action");
                grid2.setAjaxParam("catValue", action);
                grid2.setAjaxParam("id", grid2.getSelectedRows());
                grid2.getDataTable().ajax.reload();
            } else {
                grid2.setAjaxParam("customActionType", "group_action");
                grid2.setAjaxParam("catValue", action);
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
                $.cookie('Newssearch', action);
                grid2.setAjaxParam("customActionType", "group_action");
                grid2.setAjaxParam("searchValue", action);
                grid2.setAjaxParam("id", grid2.getSelectedRows());
                grid2.getDataTable().ajax.reload();
            } else if (action.length < 1) {
                $.removeCookie('Newssearch');
                grid2.setAjaxParam("customActionType", "group_action");
                grid2.setAjaxParam("searchValue", action);
                grid2.setAjaxParam("id", grid2.getSelectedRows());
                grid2.getDataTable().ajax.reload();
            }
        });

        $(document).on('click', '#newsRange', function(e) {
            e.preventDefault();
            var action = {};
            action['from'] = $('#start_date').val();
            action['to'] = $('#end_date').val();
            if (action['from'] != '' && action['to'] != '') {
                grid2.setAjaxParam("customActionType", "group_action");
                grid2.setAjaxParam("rangeFilter", action);
                grid2.setAjaxParam("id", grid2.getSelectedRows());
                grid2.getDataTable().ajax.reload();
            } else {
                grid2.setAjaxParam("customActionType", "group_action");
                grid2.setAjaxParam("rangeFilter", action);
                grid2.setAjaxParam("id", grid2.getSelectedRows());
            }
        });

        $(document).on('click', '#refresh', function(e) {
            $('#start_date').val('');
            $('#end_date').val('');
            grid2.setAjaxParam("rangeFilter", '');
            grid2.getDataTable().ajax.reload();
        });

        $('a[data-toggle="tab"][id="MenuItem3"]').on('shown.bs.tab', function(e) {
            $("#hidefilter").hide();
            e.preventDefault();
            grid2.setAjaxParam("id", grid2.getSelectedRows());
            grid2.getDataTable().ajax.reload();
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
            $.fn.DataTable.ext.pager.numbers_length = 4;
            handleRecords2();
        }
    };
}();


var gridRows3 = 0;  // Trash
var grid3 = '';
var TableDatatablesAjax3 = function() {
    var handleRecords3 = function() {

        grid3 = new Datatable();
        grid3.init({
            src: $("#datatable_ajax3"),
            onSuccess: function(grid3, response) {
                gridRows3 = response.recordsTotal;
                if (response.recordsTotal < 1) {
                    $('.deleteMass').hide();
                    $("#menu4.tab-pane .notabreocrd").show();
										$("#menu4.tab-pane .withrecords").hide();
                } else {
                    $('.deleteMass').show();
                    $("#menu4.tab-pane .notabreocrd").hide();
										$("#menu4.tab-pane .withrecords").show();
                }
                if(response.recordsTotal < 20) {
                    $('.gridjs-pages').hide();
                } else {
                    $('.gridjs-pages').show();
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
            onError: function(grid3) {},
            onDataLoad: function(grid3) {
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
                "dom": "t <'gridjs-footer' <'gridjs-pagination'i <'gridjs-pages'p>>>",
                "deferRender": true,
                // "stateSave": true, // save datatable state(pagination, sort, etc) in cookie.
                // "lengthMenu": [
                //     [10, 20, 50, 100],
                //     [10, 20, 50, 100] // change per page values here
                // ],
                "pageLength": 20, // default record count per page
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
                    'class': 'text-left Pages_title_P_1 mob-show_div',
                    'name': 'varTitle'
                }, {
                    "data": 2,
                    "bSortable": false,
                    'class': 'text-center Services_cat_P_3'
                }, {
                    "data": 3,
                    "name": 'serviceCode',
                    "class": 'text-center Services_sdate_P_3'
                }, {
                    "data": 4,
                    'class': 'text-center Pages_dactions_P_4 last_td_action mob-show_div',
                    'bSortable': false
                } ],
                "ajax": {
                    "url": window.site_url + "/powerpanel/service/get_list_trash", // ajax source
                },
                'fnCreatedRow': function(nRow, aData, iDataIndex) {
                    // $(nRow).attr('data-order', aData[5]);
                },
                "order": [
                    [1, "desc"]
                ]
            }
        });

        $('#datatable_ajax3 tbody').on('click', '.moveDwn', function() {
            var order = $(this).data('order');
            var exOrder = $('#datatable_ajax2 tbody').find('tr[data-order=' + order + ']').next().data('order');
            exOrder = (exOrder == undefined) ? order + 1 : exOrder;
            reorder(order, exOrder);
        });
        $('#datatable_ajax3 tbody').on('click', '.moveUp', function() {
            var order = $(this).data('order');
            var exOrder = $('#datatable_ajax2 tbody').find('tr[data-order=' + order + ']').prev().data('order');
            exOrder = (exOrder == undefined) ? order - 1 : exOrder;
            reorder(order, exOrder);
        });

        generateHeadfilterEvents(grid3);
        $(document).on('change', '#statusfilter', function(e) {
            e.preventDefault();
            var action = $('#statusfilter').val();
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

        $(document).on('change', '#category_id', function(e) {
            e.preventDefault();
            var action = $('#category_id').val();
            if (action != "") {
                grid3.setAjaxParam("customActionType", "group_action");
                grid3.setAjaxParam("catValue", action);
                grid3.setAjaxParam("id", grid3.getSelectedRows());
                grid3.getDataTable().ajax.reload();
            } else {
                grid3.setAjaxParam("customActionType", "group_action");
                grid3.setAjaxParam("catValue", action);
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
                $.cookie('Newssearch', action);
                grid3.setAjaxParam("customActionType", "group_action");
                grid3.setAjaxParam("searchValue", action);
                grid3.setAjaxParam("id", grid3.getSelectedRows());
                grid3.getDataTable().ajax.reload();
            } else if (action.length < 1) {
                $.removeCookie('Newssearch');
                grid3.setAjaxParam("customActionType", "group_action");
                grid3.setAjaxParam("searchValue", action);
                grid3.setAjaxParam("id", grid3.getSelectedRows());
                grid3.getDataTable().ajax.reload();
            }
        });

        $(document).on('click', '#newsRange', function(e) {
            e.preventDefault();
            var action = {};
            action['from'] = $('#start_date').val();
            action['to'] = $('#end_date').val();
            if (action['from'] != '' && action['to'] != '') {
                grid3.setAjaxParam("customActionType", "group_action");
                grid3.setAjaxParam("rangeFilter", action);
                grid3.setAjaxParam("id", grid3.getSelectedRows());
                grid3.getDataTable().ajax.reload();
            } else {
                grid3.setAjaxParam("customActionType", "group_action");
                grid3.setAjaxParam("rangeFilter", action);
                grid3.setAjaxParam("id", grid3.getSelectedRows());
            }
        });

        $(document).on('click', '#refresh', function(e) {
            $('#start_date').val('');
            $('#end_date').val('');
            grid3.setAjaxParam("rangeFilter", '');
            grid3.getDataTable().ajax.reload();
        });

        $('a[data-toggle="tab"][id="MenuItem4"]').on('shown.bs.tab', function(e) {
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
            $.fn.DataTable.ext.pager.numbers_length = 4;
            handleRecords3();
        }
    };
}();


var gridRows4 = 0;  // Favorite
var grid4 = '';
var TableDatatablesAjax4 = function() {
    var handleRecords4 = function() {

        grid4 = new Datatable();
        grid4.init({
            src: $("#datatable_ajax4"),
            onSuccess: function(grid4, response) {
                gridRows4 = response.recordsTotal;
                if (response.recordsTotal < 1) {
                    $('.deleteMass').hide();
                    $("#menu5.tab-pane .notabreocrd").show();
										$("#menu5.tab-pane .withrecords").hide();
                } else {
                    $('.deleteMass').show();
                    $("#menu5.tab-pane .notabreocrd").hide();
										$("#menu5.tab-pane .withrecords").show();
                }
                if(response.recordsTotal < 20) {
                    $('.gridjs-pages').hide();
                } else {
                    $('.gridjs-pages').show();
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
            onError: function(grid4) {},
            onDataLoad: function(grid4) {
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
                "dom": "t <'gridjs-footer' <'gridjs-pagination'i <'gridjs-pages'p>>>",
                "deferRender": true,
                // "stateSave": true, // save datatable state(pagination, sort, etc) in cookie.
                // "lengthMenu": [
                //     [10, 20, 50, 100],
                //     [10, 20, 50, 100] // change per page values here
                // ],
                "pageLength": 20, // default record count per page
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
                    'class': 'text-left Pages_title_P_1 mob-show_div',
                    'name': 'varTitle'
                }, {
                    "data": 2,
                    "bSortable": false,
                    'class': 'text-center Services_cat_P_3'
                }, {
                    "data": 3,
                    "name": 'serviceCode',
                    "class": 'text-center Services_sdate_P_3'
                }, {
                    "data": 4,
                    'class': 'text-center form-switch Pages_publish_P_3',
                    'name': 'publish',
                    'bSortable': false
                }, {
                    "data": 5,
                    'class': 'text-center Pages_dactions_P_4 last_td_action mob-show_div',
                    'bSortable': false
                } ],
                "ajax": {
                    "url": window.site_url + "/powerpanel/service/get_list_favorite", // ajax source
                },
                'fnCreatedRow': function(nRow, aData, iDataIndex) {
                    // $(nRow).attr('data-order', aData[5]);
                },
                "order": [
                    [1, "desc"]
                ]
            }
        });

        $('#datatable_ajax4 tbody').on('click', '.moveDwn', function() {
            var order = $(this).data('order');
            var exOrder = $('#datatable_ajax4 tbody').find('tr[data-order=' + order + ']').next().data('order');
            exOrder = (exOrder == undefined) ? order + 1 : exOrder;
            reorder(order, exOrder);
        });
        $('#datatable_ajax4 tbody').on('click', '.moveUp', function() {
            var order = $(this).data('order');
            var exOrder = $('#datatable_ajax4 tbody').find('tr[data-order=' + order + ']').prev().data('order');
            exOrder = (exOrder == undefined) ? order - 1 : exOrder;
            reorder(order, exOrder);
        });

        generateHeadfilterEvents(grid4);
        $(document).on('change', '#statusfilter', function(e) {
            e.preventDefault();
            var action = $('#statusfilter').val();
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

        $(document).on('change', '#category_id', function(e) {
            e.preventDefault();
            var action = $('#category_id').val();
            if (action != "") {
                grid4.setAjaxParam("customActionType", "group_action");
                grid4.setAjaxParam("catValue", action);
                grid4.setAjaxParam("id", grid4.getSelectedRows());
                grid4.getDataTable().ajax.reload();
            } else {
                grid4.setAjaxParam("customActionType", "group_action");
                grid4.setAjaxParam("catValue", action);
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
                $.cookie('Newssearch', action);
                grid4.setAjaxParam("customActionType", "group_action");
                grid4.setAjaxParam("searchValue", action);
                grid4.setAjaxParam("id", grid4.getSelectedRows());
                grid4.getDataTable().ajax.reload();
            } else if (action.length < 1) {
                $.removeCookie('Newssearch');
                grid4.setAjaxParam("customActionType", "group_action");
                grid4.setAjaxParam("searchValue", action);
                grid4.setAjaxParam("id", grid4.getSelectedRows());
                grid4.getDataTable().ajax.reload();
            }
        });

        $(document).on('click', '#newsRange', function(e) {
            e.preventDefault();
            var action = {};
            action['from'] = $('#start_date').val();
            action['to'] = $('#end_date').val();
            if (action['from'] != '' && action['to'] != '') {
                grid4.setAjaxParam("customActionType", "group_action");
                grid4.setAjaxParam("rangeFilter", action);
                grid4.setAjaxParam("id", grid4.getSelectedRows());
                grid4.getDataTable().ajax.reload();
            } else {
                grid4.setAjaxParam("customActionType", "group_action");
                grid4.setAjaxParam("rangeFilter", action);
                grid4.setAjaxParam("id", grid4.getSelectedRows());
            }
        });

        $(document).on('click', '#refresh', function(e) {
            $('#start_date').val('');
            $('#end_date').val('');
            grid4.setAjaxParam("rangeFilter", '');
            grid4.getDataTable().ajax.reload();
        });

        $('a[data-toggle="tab"][id="MenuItem5"]').on('shown.bs.tab', function(e) {
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
            $.fn.DataTable.ext.pager.numbers_length = 4;
            handleRecords4();
        }
    };
}();







$(document).ready(function() {
    let cookie = clearcookie;
    if (cookie == 'true') {
        $.removeCookie('Newssearch');
        $('#searchfilter').val('');
    }
});

$(window).on('load', function() {
	var queryString = window.location.search;
	var urlParams = new URLSearchParams(queryString);
	var sterm = urlParams.get('term');
	if(urlParams.has('term')){
		$('.filter-search').addClass('visible');
    $('#searchfilter').val(sterm);
    $('#searchfilter').trigger('keyup');	
	}else{
		if ($.cookie('Newssearch')) {
        $('#searchfilter').val($.cookie('Newssearch'));
        $('#searchfilter').trigger('keyup');
    }
	}
    
    /*let urlParams = new URLSearchParams(window.location.search);*/
    if(urlParams.has('category')){
	    let category = urlParams.get('category');
	    if (category.trim() != '' && category != null) {
	        $("#category_id").val(category);
	        $('#category_id').select2();
	        getCategoryData();
	    }
	  }
});

function getCategoryData() {
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
}


jQuery(document).ready(function() {
    $.ajaxSetup({
        headers: {
            'X-CSRF-Token': $('input[name="_token"]').val()
        }
    });
    TableDatatablesAjax.init();
    $("#hidefilter").show();
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

            var ajaxUrl = site_url + '/powerpanel/service/getSectorwiseCategoryGrid';
            jQuery.ajax({
                type: "POST",
                url: ajaxUrl,
                dataType: 'HTML',
                data: { "sectorname": sectorName},
                async: false,
                beforeSend: function() {
                	/*if(typeof choicesEl['category'] != "undefined"){
                    choicesEl['category'].destroy();
                  }*/
                },
                success: function(result) {
                    $("#category").html(result).trigger('change');
                },
                complete:function(){
                    /*let element = document.getElementById('category');
                    if(typeof element != "undefined"){
	                    const choices = new Choices(element);
	                    choicesEl['category'] = choices;
	                  }*/
                }
            });
        }
    }
}();

$('a[data-toggle="tab"][id="MenuItem2"]').on('shown.bs.tab', function(e) {
    $("#hidefilter").show();
    if (!$.fn.DataTable.isDataTable('#datatable_ajax_approved')) {
        TableDatatablesAjax1.init();
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

function reorder(curOrder, excOrder) {
    var ajaxurl = site_url + '/powerpanel/service/reorder';
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