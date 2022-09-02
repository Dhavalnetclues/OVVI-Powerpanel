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
            },
            onError: function (grid) {
            },
            onDataLoad: function (grid) {
                if ($('.pagination-panel .prev').hasClass('disabled')) {
                    $("#datatable_ajax tbody tr:first").find('.moveUp').hide();
                }
                if ($('.pagination-panel .next').hasClass('disabled')) {
                    $("#datatable_ajax tbody tr:last").find('.moveDwn').hide();
                }
                $('.make-switch').bootstrapSwitch();
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
                    }, {
                        "data": 1,
                        "bSortable": false,
                        className: 'text-center mob-show_div'
                    }, {
                        "data": 2,
                        "name": 'varTitle',
                        className: 'text-left mob-show_div'
                    },{
                        "data": 3,
                        "name": 'dtDateTime',
                        className: 'text-center'
                    }, {
                        "data": 4,
                        className: 'text-center',
                        "bSortable": false
                    }, {
                        "data": 5,
                        "bSortable": false,
                        className: 'text-center publish_switch'
                    },
                    {
                        "data": 6,
                        "bSortable": false,
                        className: 'text-right last_td_action mob-show_div'
                    },
                ],
                "ajax": {
                    "url": window.site_url + "/powerpanel/rfp/get_list", // ajax source
                },
                'fnCreatedRow': function (nRow, aData, iDataIndex) {
                    $(nRow).attr('data-order', aData[6]);
                },
                "order": [
                    [3, "desc"]
                ]
            }
        });
        $('#datatable_ajax tbody').on('click', '.moveDwn', function () {
            var order = $(this).data('order');
            var exOrder = $('#datatable_ajax tbody').find('tr[data-order=' + order + ']').next().data('order');
            exOrder = (exOrder == undefined) ? order + 1 : exOrder;
            reorder(order, exOrder);
        });
        $('#datatable_ajax tbody').on('click', '.moveUp', function () {
            var order = $(this).data('order');
            var exOrder = $('#datatable_ajax tbody').find('tr[data-order=' + order + ']').prev().data('order');
            exOrder = (exOrder == undefined) ? order - 1 : exOrder;
            reorder(order, exOrder);
        });
        generateHeadfilterEvents(grid);
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

        $(document).on("switchChange.bootstrapSwitch", ".publish", function (event, state) {
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
                $.cookie('RFPsearch', action);
                grid.setAjaxParam("customActionType", "group_action");
                grid.setAjaxParam("searchValue", action);
                grid.setAjaxParam("id", grid.getSelectedRows());
                grid.getDataTable().ajax.reload();
            } else if (action.length < 1) {
                $.removeCookie('RFPsearch');
                grid.setAjaxParam("customActionType", "group_action");
                grid.setAjaxParam("searchValue", action);
                grid.setAjaxParam("id", grid.getSelectedRows());
                grid.getDataTable().ajax.reload();
            }
        });
        $(document).on('click', '#rfpRange', function (e) {
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
        $(document).on('click', '#refresh', function (e) {
            $('#start_date').val('');
            $('#end_date').val('');
            grid.setAjaxParam("rangeFilter", '');
            grid.getDataTable().ajax.reload();
        });

        $('a[data-toggle="tab"][id="MenuItem1"]').on('shown.bs.tab', function (e) {
            $("#hidefilter").show();
            e.preventDefault();
            grid.setAjaxParam("id", grid.getSelectedRows());
            grid.getDataTable().ajax.reload();
        });

        if (!showChecker) {
            if (grid != "") {
                grid.getDataTable().column(5).visible(false);
            } else {
                if ($.cookie('RFP_publish_P_5') == 'Y') {
                    grid.getDataTable().column(5).visible(true);
                }
            }
        }

        $(document).on("click", '#grpChkBox .checkbox_P', function () {
            if ($(this).prop("checked") == true) {
                var datatable_P = $(this).attr("name").split("_");
                grid.getDataTable().column(datatable_P[3]).visible(true);
                Cookies.set($(this).attr("name"), 'Y');
                grid.getDataTable().ajax.reload();
            } else {
                var datatable_P = $(this).attr("name").split("_");
                grid.getDataTable().column(datatable_P[3]).visible(false);
                Cookies.set($(this).attr("name"), 'N');
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
            src: $("#datatable_ajax1"),
            onSuccess: function (grid1, response) {
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
            },
            onError: function (grid1) {
            },
            onDataLoad: function (grid1) {
                if ($('.pagination-panel .prev').hasClass('disabled')) {
                    $("#datatable_ajax1 tbody tr:first").find('.moveUp').hide();
                }
                if ($('.pagination-panel .next').hasClass('disabled')) {
                    $("#datatable_ajax1 tbody tr:last").find('.moveDwn').hide();
                }
                $('.make-switch').bootstrapSwitch();
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
                        "bSortable": false,
                        className: 'text-center mob-show_div'
                    }, {
                        "data": 1,
                        "name": 'varTitle',
                        className: 'text-left mob-show_div',
                        "bSortable": true
                    },{
                        "data": 2,
                        "name": 'dtDateTime',
                        className: 'text-center'
                    }, {
                        "data": 3,
                        className: 'text-center',
                        "bSortable": false
                    }, 
                    
                    {
                        "data": 4,
                        "bSortable": false,
                        className: 'text-right last_td_action mob-show_div'
                    },
                ],
                "ajax": {
                    "url": window.site_url + "/powerpanel/rfp/get_list_New", // ajax source
                },
                'fnCreatedRow': function (nRow, aData, iDataIndex) {
                    $(nRow).attr('data-order', aData[4]);
                },
                "order": [
                    [2, "desc"]
                ]
            }
        });
        $('#datatable_ajax1 tbody').on('click', '.moveDwn', function () {
            var order = $(this).data('order');
            var exOrder = $('#datatable_ajax1 tbody').find('tr[data-order=' + order + ']').next().data('order');
            exOrder = (exOrder == undefined) ? order + 1 : exOrder;
            reorder(order, exOrder);
        });
        $('#datatable_ajax1 tbody').on('click', '.moveUp', function () {
            var order = $(this).data('order');
            var exOrder = $('#datatable_ajax1 tbody').find('tr[data-order=' + order + ']').prev().data('order');
            exOrder = (exOrder == undefined) ? order - 1 : exOrder;
            reorder(order, exOrder);
        });
        generateHeadfilterEvents(grid1);
        $(document).on('change', '#statusfilter', function (e) {
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
        $(document).on("switchChange.bootstrapSwitch", ".publish", function (event, state) {
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

        $(document).on('keyup', '#searchfilter', function (e) {
            e.preventDefault();
            var action = $('#searchfilter').val();
            if (action.length >= 2) {
                $.cookie('RFPsearch', action);
                grid1.setAjaxParam("customActionType", "group_action");
                grid1.setAjaxParam("searchValue", action);
                grid1.setAjaxParam("id", grid1.getSelectedRows());
                grid1.getDataTable().ajax.reload();
            } else if (action.length < 1) {
                $.removeCookie('RFPsearch');
                grid1.setAjaxParam("customActionType", "group_action");
                grid1.setAjaxParam("searchValue", action);
                grid1.setAjaxParam("id", grid1.getSelectedRows());
                grid1.getDataTable().ajax.reload();
            }
        });
        $(document).on('click', '#rfpRange', function (e) {
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
        $(document).on('click', '#refresh', function (e) {
            $('#start_date').val('');
            $('#end_date').val('');
            grid1.setAjaxParam("rangeFilter", '');
            grid1.getDataTable().ajax.reload();
        });

        $('a[data-toggle="tab"][id="MenuItem1"]').on('shown.bs.tab', function (e) {
            e.preventDefault();
            grid.setAjaxParam("id", grid.getSelectedRows());
            grid.getDataTable().ajax.reload();
        });

        $('a[data-toggle="tab"][id="MenuItem2"]').on('shown.bs.tab', function (e) {
            $("#hidefilter").show();
            e.preventDefault();
            grid1.setAjaxParam("id", grid1.getSelectedRows());
            grid1.getDataTable().ajax.reload();
        });

        $(document).on("click", '#AgrpChkBox .checkbox_A', function () {
            if ($(this).prop("checked") == true) {
                var datatable_A = $(this).attr("name").split("_");
                grid1.getDataTable().column(datatable_A[3]).visible(true);
                Cookies.set($(this).attr("name"), 'Y');
                grid1.getDataTable().ajax.reload();
            } else {
                var datatable_A = $(this).attr("name").split("_");
                grid1.getDataTable().column(datatable_A[3]).visible(false);
                Cookies.set($(this).attr("name"), 'N');
                grid1.getDataTable().ajax.reload();
            }
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


var gridRows2 = 0;
var grid2 = '';
var TableDatatablesAjax2 = function () {
    var handleRecords2 = function () {

        grid2 = new Datatable();
        grid2.init({
            src: $("#datatable_ajax2"),
            onSuccess: function (grid2, response) {
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
            },
            onError: function (grid2) {
            },
            onDataLoad: function (grid2) {
                if ($('.pagination-panel .prev').hasClass('disabled')) {
                    $("#datatable_ajax2 tbody tr:first").find('.moveUp').hide();
                }
                if ($('.pagination-panel .next').hasClass('disabled')) {
                    $("#datatable_ajax2 tbody tr:last").find('.moveDwn').hide();
                }
                $('.make-switch').bootstrapSwitch();
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
                    }, {
                        "data": 1,
                        "bSortable": false,
                        className: 'text-center mob-show_div'
                    }, {
                        "data": 2,
                        "bSortable": false,
                        className: 'text-center mob-show_div'
                    }, {
                        "data": 3,
                        "name": 'dtDateTime',
                        className: 'text-center'
                    }, {
                        "data": 4,
                        className: 'text-center',
                        "bSortable": false
                    }, {
                        "data": 5,
                        "bSortable": false,
                        className: 'text-center publish_switch'
                    },
                    {
                        "data": 6,
                        "bSortable": false,
                        className: 'text-right last_td_action mob-show_div'
                    },
                ],
                "ajax": {
                    "url": window.site_url + "/powerpanel/rfp/get_list_draft", // ajax source
                },
                'fnCreatedRow': function (nRow, aData, iDataIndex) {
                    $(nRow).attr('data-order', aData[6]);
                },
                "order": [
                    [3, "desc"]
                ]
            }
        });
        $('#datatable_ajax2 tbody').on('click', '.moveDwn', function () {
            var order = $(this).data('order');
            var exOrder = $('#datatable_ajax2 tbody').find('tr[data-order=' + order + ']').next().data('order');
            exOrder = (exOrder == undefined) ? order + 1 : exOrder;
            reorder(order, exOrder);
        });
        $('#datatable_ajax2 tbody').on('click', '.moveUp', function () {
            var order = $(this).data('order');
            var exOrder = $('#datatable_ajax2 tbody').find('tr[data-order=' + order + ']').prev().data('order');
            exOrder = (exOrder == undefined) ? order - 1 : exOrder;
            reorder(order, exOrder);
        });
        generateHeadfilterEvents(grid2);
        $(document).on('change', '#statusfilter', function (e) {
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

        $(document).on('change', '#category_id', function (e) {
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

        $(document).on("switchChange.bootstrapSwitch", ".publish", function (event, state) {
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
                    grid2.getDataTable().ajax.reload(null, false);
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
                $.cookie('RFPsearch', action);
                grid2.setAjaxParam("customActionType", "group_action");
                grid2.setAjaxParam("searchValue", action);
                grid2.setAjaxParam("id", grid2.getSelectedRows());
                grid2.getDataTable().ajax.reload();
            } else if (action.length < 1) {
                $.removeCookie('RFPsearch');
                grid2.setAjaxParam("customActionType", "group_action");
                grid2.setAjaxParam("searchValue", action);
                grid2.setAjaxParam("id", grid2.getSelectedRows());
                grid2.getDataTable().ajax.reload();
            }
        });
        $(document).on('click', '#rfpRange', function (e) {
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
        $(document).on('click', '#refresh', function (e) {
            $('#start_date').val('');
            $('#end_date').val('');
            grid2.setAjaxParam("rangeFilter", '');
            grid2.getDataTable().ajax.reload();
        });

        $('a[data-toggle="tab"][id="MenuItem3"]').on('shown.bs.tab', function (e) {
            $("#hidefilter").hide();
            e.preventDefault();
            grid2.setAjaxParam("id", grid2.getSelectedRows());
            grid2.getDataTable().ajax.reload();
        });

        $(document).on("click", '#DgrpChkBox .checkbox_D', function () {
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
        grid2.getDataTable().columns().iterator('column', function (ctx, idx) {
            $(grid2.getDataTable().column(idx).header()).append('<span class="sort-icon"/>');
        });
    }
    return {
        //main function to initiate the module
        init: function () {
            handleRecords2();
        }
    };
}();


var gridRows3 = 0;
var grid3 = '';
var TableDatatablesAjax3 = function () {
    var handleRecords3 = function () {

        grid3 = new Datatable();
        grid3.init({
            src: $("#datatable_ajax3"),
            onSuccess: function (grid3, response) {
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
            },
            onError: function (grid3) {
            },
            onDataLoad: function (grid3) {
                if ($('.pagination-panel .prev').hasClass('disabled')) {
                    $("#datatable_ajax3 tbody tr:first").find('.moveUp').hide();
                }
                if ($('.pagination-panel .next').hasClass('disabled')) {
                    $("#datatable_ajax3 tbody tr:last").find('.moveDwn').hide();
                }
                $('.make-switch').bootstrapSwitch();
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
                    }, {
                        "data": 1,
                        "bSortable": false,
                        className: 'text-center mob-show_div'
                    }, {
                        "data": 2,
                        "bSortable": false,
                        className: 'text-center mob-show_div'
                    }, {
                        "data": 3,
                        "name": 'dtDateTime',
                        className: 'text-center'
                    }, {
                        "data": 4,
                        className: 'text-center',
                        "bSortable": false
                    },
                    {
                        "data": 5,
                        "bSortable": false,
                        className: 'text-right last_td_action mob-show_div'
                    },
                ],
                "ajax": {
                    "url": window.site_url + "/powerpanel/rfp/get_list_trash", // ajax source
                },
                'fnCreatedRow': function (nRow, aData, iDataIndex) {
                    $(nRow).attr('data-order', aData[5]);
                },
                "order": [
                    [3, "desc"]
                ]
            }
        });
        $('#datatable_ajax3 tbody').on('click', '.moveDwn', function () {
            var order = $(this).data('order');
            var exOrder = $('#datatable_ajax2 tbody').find('tr[data-order=' + order + ']').next().data('order');
            exOrder = (exOrder == undefined) ? order + 1 : exOrder;
            reorder(order, exOrder);
        });
        $('#datatable_ajax3 tbody').on('click', '.moveUp', function () {
            var order = $(this).data('order');
            var exOrder = $('#datatable_ajax2 tbody').find('tr[data-order=' + order + ']').prev().data('order');
            exOrder = (exOrder == undefined) ? order - 1 : exOrder;
            reorder(order, exOrder);
        });
        generateHeadfilterEvents(grid3);
        $(document).on('change', '#statusfilter', function (e) {
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

        $(document).on('change', '#category_id', function (e) {
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

        $(document).on("switchChange.bootstrapSwitch", ".publish", function (event, state) {
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
                    grid3.getDataTable().ajax.reload(null, false);
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
                $.cookie('RFPsearch', action);
                grid3.setAjaxParam("customActionType", "group_action");
                grid3.setAjaxParam("searchValue", action);
                grid3.setAjaxParam("id", grid3.getSelectedRows());
                grid3.getDataTable().ajax.reload();
            } else if (action.length < 1) {
                $.removeCookie('RFPsearch');
                grid3.setAjaxParam("customActionType", "group_action");
                grid3.setAjaxParam("searchValue", action);
                grid3.setAjaxParam("id", grid3.getSelectedRows());
                grid3.getDataTable().ajax.reload();
            }
        });
        $(document).on('click', '#rfpRange', function (e) {
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
        $(document).on('click', '#refresh', function (e) {
            $('#start_date').val('');
            $('#end_date').val('');
            grid3.setAjaxParam("rangeFilter", '');
            grid3.getDataTable().ajax.reload();
        });

        $('a[data-toggle="tab"][id="MenuItem4"]').on('shown.bs.tab', function (e) {
            $("#hidefilter").hide();
            e.preventDefault();
            grid3.setAjaxParam("id", grid3.getSelectedRows());
            grid3.getDataTable().ajax.reload();
        });

        $(document).on("click", '#TgrpChkBox .checkbox_T', function () {
            if ($(this).prop("checked") == true) {
                var datatable_T = $(this).attr("name").split("_");
                grid3.getDataTable().column(datatable_T[3]).visible(true);
                Cookies.set($(this).attr("name"), 'Y');
                grid3.getDataTable().ajax.reload();
            } else {
                var datatable_T = $(this).attr("name").split("_");
                grid3.getDataTable().column(datatable_T[3]).visible(false);
                Cookies.set($(this).attr("name"), 'N');
                grid3.getDataTable().ajax.reload();
            }
        });

        grid3.setAjaxParam("customActionType", "group_action");
        grid3.clearAjaxParams();
        grid3.getDataTable().columns().iterator('column', function (ctx, idx) {
            $(grid3.getDataTable().column(idx).header()).append('<span class="sort-icon"/>');
        });
    }
    return {
        //main function to initiate the module
        init: function () {
            handleRecords3();
        }
    };
}();


var gridRows4 = 0;
var grid4 = '';
var TableDatatablesAjax4 = function () {
    var handleRecords4 = function () {

        grid4 = new Datatable();
        grid4.init({
            src: $("#datatable_ajax4"),
            onSuccess: function (grid4, response) {
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
            },
            onError: function (grid4) {
            },
            onDataLoad: function (grid4) {
                if ($('.pagination-panel .prev').hasClass('disabled')) {
                    $("#datatable_ajax4 tbody tr:first").find('.moveUp').hide();
                }
                if ($('.pagination-panel .next').hasClass('disabled')) {
                    $("#datatable_ajax4 tbody tr:last").find('.moveDwn').hide();
                }
                $('.make-switch').bootstrapSwitch();
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
                    }, {
                        "data": 1,
                        "bSortable": false,
                        className: 'text-center mob-show_div'
                    }, {
                        "data": 2,
                        "name": 'varTitle',
                        className: 'text-left mob-show_div'
                    },  {
                        "data": 3,
                        "name": 'dtDateTime',
                        className: 'text-center'
                    }, {
                        "data": 4,
                        className: 'text-center',
                        "bSortable": false
                    },
                    {
                        "data": 5,
                        "bSortable": false,
                        className: 'text-right last_td_action mob-show_div'
                    },
                ],
                "ajax": {
                    "url": window.site_url + "/powerpanel/rfp/get_list_favorite", // ajax source
                },
                'fnCreatedRow': function (nRow, aData, iDataIndex) {
                    $(nRow).attr('data-order', aData[5]);
                },
                "order": [
                    [3, "desc"]
                ]
            }
        });
        $('#datatable_ajax4 tbody').on('click', '.moveDwn', function () {
            var order = $(this).data('order');
            var exOrder = $('#datatable_ajax4 tbody').find('tr[data-order=' + order + ']').next().data('order');
            exOrder = (exOrder == undefined) ? order + 1 : exOrder;
            reorder(order, exOrder);
        });
        $('#datatable_ajax4 tbody').on('click', '.moveUp', function () {
            var order = $(this).data('order');
            var exOrder = $('#datatable_ajax4 tbody').find('tr[data-order=' + order + ']').prev().data('order');
            exOrder = (exOrder == undefined) ? order - 1 : exOrder;
            reorder(order, exOrder);
        });
        generateHeadfilterEvents(grid4);
        $(document).on('change', '#statusfilter', function (e) {
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

        $(document).on('change', '#category_id', function (e) {
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

        $(document).on("switchChange.bootstrapSwitch", ".publish", function (event, state) {
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
                    grid4.getDataTable().ajax.reload(null, false);
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
                $.cookie('RFPsearch', action);
                grid4.setAjaxParam("customActionType", "group_action");
                grid4.setAjaxParam("searchValue", action);
                grid4.setAjaxParam("id", grid4.getSelectedRows());
                grid4.getDataTable().ajax.reload();
            } else if (action.length < 1) {
                $.removeCookie('RFPsearch');
                grid4.setAjaxParam("customActionType", "group_action");
                grid4.setAjaxParam("searchValue", action);
                grid4.setAjaxParam("id", grid4.getSelectedRows());
                grid4.getDataTable().ajax.reload();
            }
        });
        $(document).on('click', '#rfpRange', function (e) {
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
        $(document).on('click', '#refresh', function (e) {
            $('#start_date').val('');
            $('#end_date').val('');
            grid4.setAjaxParam("rangeFilter", '');
            grid4.getDataTable().ajax.reload();
        });

        $('a[data-toggle="tab"][id="MenuItem5"]').on('shown.bs.tab', function (e) {
            $("#hidefilter").hide();
            e.preventDefault();
            grid4.setAjaxParam("id", grid4.getSelectedRows());
            grid4.getDataTable().ajax.reload();
        });

        $(document).on("click", '#FgrpChkBox .checkbox_F', function () {
            if ($(this).prop("checked") == true) {
                var datatable_F = $(this).attr("name").split("_");
                grid4.getDataTable().column(datatable_F[3]).visible(true);
                Cookies.set($(this).attr("name"), 'Y');
                grid4.getDataTable().ajax.reload();
            } else {
                var datatable_F = $(this).attr("name").split("_");
                grid4.getDataTable().column(datatable_F[3]).visible(false);
                Cookies.set($(this).attr("name"), 'N');
                grid4.getDataTable().ajax.reload();
            }
        });

        grid4.setAjaxParam("customActionType", "group_action");
        grid4.clearAjaxParams();
        grid4.getDataTable().columns().iterator('column', function (ctx, idx) {
            $(grid4.getDataTable().column(idx).header()).append('<span class="sort-icon"/>');
        });
    }
    return {
        //main function to initiate the module
        init: function () {
            handleRecords4();
        }
    };
}();



$(window).on('load', function () {
    if ($.cookie('RFPsearch')) {
        $('#searchfilter').val($.cookie('RFPsearch'));
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
    $("#hidefilter").show();

});

$('a[data-toggle="tab"][id="MenuItem2"]').on('shown.bs.tab', function (e) {
    $("#hidefilter").show();
    if (!$.fn.DataTable.isDataTable('#datatable_ajax1')) {
        TableDatatablesAjax1.init();
    }
});

$('a[data-toggle="tab"][id="MenuItem3"]').on('shown.bs.tab', function (e) {
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

$('a[data-toggle="tab"][id="MenuItem4"]').on('shown.bs.tab', function (e) {
    $("#hidefilter").hide();
    if (!$.fn.DataTable.isDataTable('#datatable_ajax3')) {
        TableDatatablesAjax3.init();
    }
});

$('a[data-toggle="tab"][id="MenuItem5"]').on('shown.bs.tab', function (e) {
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
function generateHeadfilterEvents(gridvariable){

	$(document).off('.list_head_filter').on('click', '.list_head_filter', function (e) {
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
	$(document).on('change', '.list_head_filter', function (e) {
        	gridvariable.setAjaxParam("customFilterIdentity","");
  });
  $('.list_head_filter').trigger('change');
}
function reorder(curOrder, excOrder) {
    var ajaxurl = site_url + '/powerpanel/rfp/reorder';
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