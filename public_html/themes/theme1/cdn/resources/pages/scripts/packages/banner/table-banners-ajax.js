var gridRows = 0;
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
                $('.make-switch').bootstrapSwitch();
            },
            loadingMessage: 'Loading...',
            dataTable: {
                "deferRender": true,
                "stateSave": true, // save datatable state(pagination, sort, etc) in cookie.
                "lengthMenu": [
                    [10, 20, 50, 100],
                    [10, 20, 50, 100] // change per page values here
                ],
                "pageLength": 100, // default record count per page
                //Code for sorting 
                "serverSide": true,
                "columns": [
                    { "data": 0, className: 'td_checker', 'bSortable': false },
                    {
                        "data": 1,
                        'class': 'text-center mob-show_div',
                        'bSortable': false
                    },
                    { "data": 2, 'name': 'varTitle', className: 'text-left Banners_title_P_2 mob-show_div' },
                    { "data": 3, 'bSortable': false, className: 'text-center Banners_image_P_3' },
                    { "data": 4, 'bSortable': false, 'name': 'varBannerType', className: 'text-center Banners_bannertype_P_4' },
                    { "data": 5, 'bSortable': false, className: 'text-center Banners_page_P_5' },
                    {
                        "data": 6,
                        className: 'text-center Banners_sdate_P_6',
                        "name": 'dtDateTime',
                        "bSortable": true
                    },
                    { "data": 7, className: 'text-center Banners_order_P_7', 'name': 'intDisplayOrder' },
                    { "data": 8, 'bSortable': false, className: 'text-center publish_switch Banners_publish_P_8' },
                    { "data": 9, 'bSortable': false, className: 'text-right Banners_dactions_P_9 last_td_action mob-show_div' },
                ],
                "ajax": {
                    "url": window.site_url + "/powerpanel/banners/get_list", // ajax source
                },
                'fnCreatedRow': function(nRow, aData, iDataIndex) {
                    // $(nRow).attr('data-order', aData[9]);
                },
                "order": [
                    [7, "asc"]
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
        /*********/
        $(document).on('change', '#bannerFilter', function(e) {
            e.preventDefault();
            var action = $(this).val();
            if (action != "") {
                grid.setAjaxParam("customActionType", "group_action");
                grid.setAjaxParam("bannerFilter", action);
                grid.setAjaxParam("id", grid.getSelectedRows());
                grid.getDataTable().ajax.reload();
            } else {
                grid.setAjaxParam("customActionType", "group_action");
                grid.setAjaxParam("bannerFilter", action);
                grid.setAjaxParam("id", grid.getSelectedRows());
            }
        });
        /*********/
        //This code for search filter
        $(document).on('keyup', '#searchfilter', function(e) {
            e.preventDefault();
            var action = $('#searchfilter').val();
            if (action.length >= 2) {
                $.cookie('bannerSearch', action);
                grid.setAjaxParam("customActionType", "group_action");
                grid.setAjaxParam("searchValue", action);
                grid.setAjaxParam("id", grid.getSelectedRows());
                grid.getDataTable().ajax.reload();
            } else if (action.length < 1) {
                $.removeCookie('bannerSearch');
                grid.setAjaxParam("customActionType", "group_action");
                grid.setAjaxParam("searchValue", action);
                grid.setAjaxParam("id", grid.getSelectedRows());
                grid.getDataTable().ajax.reload();
            }
        });
        $(document).on('change', '#statusfilter', function(e) {
            e.preventDefault();
            var action = $('#statusfilter').val();
            if (action != "") {
                grid.setAjaxParam("customActionType", "group_action");
                grid.setAjaxParam("customActionName", action);
                grid.setAjaxParam("id", grid.getSelectedRows());
                grid.getDataTable().ajax.reload();
            } else {
                grid.setAjaxParam("customActionType", "group_action");
                grid.setAjaxParam("customActionName", action);
                grid.setAjaxParam("id", grid.getSelectedRows());
            }
        });
        $(document).on('change', '#bannerFilterType', function(e) {
            e.preventDefault();
            var action = $('#bannerFilterType').val();
            if (action != "") {
                grid.setAjaxParam("customActionType", "group_action");
                grid.setAjaxParam("bannerFilterType", action);
                grid.setAjaxParam("id", grid.getSelectedRows());
                grid.getDataTable().ajax.reload();
            } else {
                grid.setAjaxParam("customActionType", "group_action");
                grid.setAjaxParam("customActionName", action);
                grid.setAjaxParam("id", grid.getSelectedRows());
            }
        });
        $(document).on('change', '#pageFilter', function(e) {
            e.preventDefault();
            var action = $(this).val();
            if (action != "") {
                grid.setAjaxParam("customActionType", "group_action");
                grid.setAjaxParam("pageFilter", action);
                grid.setAjaxParam("id", grid.getSelectedRows());
                grid.getDataTable().ajax.reload();
            } else {
                grid.setAjaxParam("customActionType", "group_action");
                grid.setAjaxParam("pageFilter", action);
                grid.setAjaxParam("id", grid.getSelectedRows());
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
                data: { alias: alias, val: val },
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
        $(document).on('click', '.defaultBanner', function(e) {
            e.preventDefault();
            var controller = $(this).data('controller');
            var alias = $(this).data('alias');
            var val = $(this).data('value');
            var url = site_url + '/' + controller + '/makeDefault';
            $.ajax({
                url: url,
                data: { alias: alias, val: val },
                type: "POST",
                dataType: "HTML",
                success: function(data) {
                    grid.getDataTable().ajax.reload();
                },
                error: function() {
                    console.log('error!');
                }
            });
        });
        $('a[data-toggle="tab"][id="MenuItem1"]').on('shown.bs.tab', function(e) {
            $("#hidefilter").show();
            e.preventDefault();
            grid.setAjaxParam("id", grid.getSelectedRows());
            grid.getDataTable().ajax.reload();
        });

        if (!showChecker) {
            grid.getDataTable().column(8).visible(false);
            grid.getDataTable().column(9).visible(false);
        } else {
            grid.getDataTable().column(8).visible(true);
            grid.getDataTable().column(9).visible(true);
        }

        grid.setAjaxParam("customActionType", "group_action");
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
var grid1Rows = 0;
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
                if ($('.pagination-panel .prev').hasClass('disabled')) {
                    $("#datatable_ajax_approved tbody tr:first").find('.moveUp').hide();
                }
                if ($('.pagination-panel .next').hasClass('disabled')) {
                    $("#datatable_ajax_approved tbody tr:last").find('.moveDwn').hide();
                }
                $('.make-switch').bootstrapSwitch();
            },
            loadingMessage: 'Loading...',
            dataTable: {
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
                        'class': 'text-center mob-show_div',
                        'bSortable': false
                    },
                    { "data": 1, 'name': 'varTitle', className: 'text-left Banners_title_A_2 mob-show_div' },
                    { "data": 2, 'bSortable': false, className: 'text-center Banners_image_A_3' },
                    { "data": 3, 'name': 'varBannerType', className: 'text-center Banners_bannertype_A_4' },
                    { "data": 4, 'bSortable': false, className: 'text-center Banners_page_A_5' },
                    {
                        "data": 5,
                        className: 'text-center Banners_sdate_A_6',
                        "name": 'dtDateTime',
                        "bSortable": false
                    },
                    { "data": 6, 'bSortable': false, className: 'text-right Banners_dactions_A_9 last_td_action mob-show_div' },
                ],
                // "columnDefs": [{
                //     "targets": [6],
                //     "visible": false,
                //     "searchable": false
                // }],
                "ajax": {
                    "url": window.site_url + "/powerpanel/banners/get_list_New", // ajax source
                },
                'fnCreatedRow': function(nRow, aData, iDataIndex) {
                    $(nRow).attr('data-order', aData[5]);
                },
                "order": [
                    [5, "desc"]
                ]
            }
        });
        $('#datatable_ajax_approved tbody').on('click', '.moveDwn', function() {
            var order = $(this).data('order');
            var exOrder = $('#banners_datatable_ajax1 tbody').find('tr[data-order=' + order + ']').next().data('order');
            exOrder = (exOrder == undefined) ? order + 1 : exOrder;
            reorder(order, exOrder);
        });
        $('#datatable_ajax_approved tbody').on('click', '.moveUp', function() {
            var order = $(this).data('order');
            var exOrder = $('#banners_datatable_ajax1 tbody').find('tr[data-order=' + order + ']').prev().data('order');
            exOrder = (exOrder == undefined) ? order - 1 : exOrder;
            reorder(order, exOrder);
        });
        /*********/
        $(document).on('change', '#bannerFilter', function(e) {
            e.preventDefault();
            var action = $(this).val();
            if (action != "") {
                grid1.setAjaxParam("customActionType", "group_action");
                grid1.setAjaxParam("bannerFilter", action);
                grid1.setAjaxParam("id", grid1.getSelectedRows());
                grid1.getDataTable().ajax.reload();
            } else {
                grid1.setAjaxParam("customActionType", "group_action");
                grid1.setAjaxParam("bannerFilter", action);
                grid1.setAjaxParam("id", grid1.getSelectedRows());
            }
        });
        /*********/
        //This code for search filter
        $(document).on('keyup', '#searchfilter', function(e) {
            e.preventDefault();
            var action = $('#searchfilter').val();
            if (action.length >= 2) {
                $.cookie('bannerSearch', action);
                grid1.setAjaxParam("customActionType", "group_action");
                grid1.setAjaxParam("searchValue", action);
                grid1.setAjaxParam("id", grid1.getSelectedRows());
                grid1.getDataTable().ajax.reload();
            } else if (action.length < 1) {
                $.removeCookie('bannerSearch');
                grid1.setAjaxParam("customActionType", "group_action");
                grid1.setAjaxParam("searchValue", action);
                grid1.setAjaxParam("id", grid1.getSelectedRows());
                grid1.getDataTable().ajax.reload();
            }
        });
        $(document).on('change', '#statusfilter', function(e) {
            e.preventDefault();
            var action = $('#statusfilter').val();
            if (action != "") {
                grid1.setAjaxParam("customActionType", "group_action");
                grid1.setAjaxParam("customActionName", action);
                grid1.setAjaxParam("id", grid1.getSelectedRows());
                grid1.getDataTable().ajax.reload();
            } else {
                grid1.setAjaxParam("customActionType", "group_action");
                grid1.setAjaxParam("customActionName", action);
                grid1.setAjaxParam("id", grid1.getSelectedRows());
            }
        });
        $(document).on('change', '#bannerFilterType', function(e) {
            e.preventDefault();
            var action = $('#bannerFilterType').val();
            if (action != "") {
                grid1.setAjaxParam("customActionType", "group_action");
                grid1.setAjaxParam("bannerFilterType", action);
                grid1.setAjaxParam("id", grid1.getSelectedRows());
                grid1.getDataTable().ajax.reload();
            } else {
                grid1.setAjaxParam("customActionType", "group_action");
                grid1.setAjaxParam("customActionName", action);
                grid1.setAjaxParam("id", grid1.getSelectedRows());
            }
        });
        $(document).on('change', '#pageFilter', function(e) {
            e.preventDefault();
            var action = $(this).val();
            if (action != "") {
                grid1.setAjaxParam("customActionType", "group_action");
                grid1.setAjaxParam("pageFilter", action);
                grid1.setAjaxParam("id", grid1.getSelectedRows());
                grid1.getDataTable().ajax.reload();
            } else {
                grid1.setAjaxParam("customActionType", "group_action");
                grid1.setAjaxParam("pageFilter", action);
                grid1.setAjaxParam("id", grid1.getSelectedRows());
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
                data: { alias: alias, val: val },
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
        $(document).on('click', '.defaultBanner', function(e) {
            e.preventDefault();
            var controller = $(this).data('controller');
            var alias = $(this).data('alias');
            var val = $(this).data('value');
            var url = site_url + '/' + controller + '/makeDefault';
            $.ajax({
                url: url,
                data: { alias: alias, val: val },
                type: "POST",
                dataType: "HTML",
                success: function(data) {
                    grid1.getDataTable().ajax.reload();
                },
                error: function() {
                    console.log('error!');
                }
            });
        });
        $('a[data-toggle="tab"][id="MenuItem1"]').on('shown.bs.tab', function(e) {
            $("#hidefilter").show();
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
            handleRecords1();
        }
    };
}();
var grid2Rows = 0;
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
                $('.make-switch').bootstrapSwitch();
            },
            loadingMessage: 'Loading...',
            dataTable: {
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
                        'class': 'text-center mob-show_div',
                        'bSortable': false
                    },
                    { "data": 1, 'name': 'varTitle', className: 'text-left Banners_title_D_2 mob-show_div' },
                    { "data": 2, 'bSortable': false, className: 'text-center Banners_image_D_3' },
                    { "data": 3, 'name': 'varBannerType', className: 'text-center Banners_bannertype_D_4' },
                    { "data": 4, 'bSortable': false, className: 'text-center Banners_page_D_5' },
                    {
                        "data": 5,
                        className: 'text-center Banners_sdate_D_6',
                        "name": 'dtDateTime',
                        "bSortable": true
                    },
                    { "data": 6, 'bSortable': false, className: 'text-center publish_switch Banners_publish_D_8' },
                    { "data": 7, 'bSortable': false, className: 'text-right Banners_dactions_D_9 last_td_action mob-show_div' },
                    { "data": 8, 'bSortable': false }
                ],
                "columnDefs": [{
                    "targets": [8],
                    "visible": false,
                    "searchable": false
                }],
                "ajax": {
                    "url": window.site_url + "/powerpanel/banners/get_list_draft", // ajax source
                },
                'fnCreatedRow': function(nRow, aData, iDataIndex) {
                    $(nRow).attr('data-order', aData[7]);
                },
                "order": [
                    [5, "desc"]
                ]
            }
        });
        $('#datatable_ajax2 tbody').on('click', '.moveDwn', function() {
            var order = $(this).data('order');
            exOrder = order + 1;
            reorder(order, exOrder);
        });
        $('#datatable_ajax2 tbody').on('click', '.moveUp', function() {
            var order = $(this).data('order');
            exOrder = order - 1;
            reorder(order, exOrder);
        });
        /*********/
        $(document).on('change', '#bannerFilter', function(e) {
            e.preventDefault();
            var action = $(this).val();
            if (action != "") {
                grid2.setAjaxParam("customActionType", "group_action");
                grid2.setAjaxParam("bannerFilter", action);
                grid2.setAjaxParam("id", grid2.getSelectedRows());
                grid2.getDataTable().ajax.reload();
            } else {
                grid2.setAjaxParam("customActionType", "group_action");
                grid2.setAjaxParam("bannerFilter", action);
                grid2.setAjaxParam("id", grid2.getSelectedRows());
            }
        });
        /*********/
        //This code for search filter
        $(document).on('keyup', '#searchfilter', function(e) {
            e.preventDefault();
            var action = $('#searchfilter').val();
            if (action.length >= 2) {
                $.cookie('bannerSearch', action);
                grid2.setAjaxParam("customActionType", "group_action");
                grid2.setAjaxParam("searchValue", action);
                grid2.setAjaxParam("id", grid2.getSelectedRows());
                grid2.getDataTable().ajax.reload();
            } else if (action.length < 1) {
                $.removeCookie('bannerSearch');
                grid2.setAjaxParam("customActionType", "group_action");
                grid2.setAjaxParam("searchValue", action);
                grid2.setAjaxParam("id", grid2.getSelectedRows());
                grid2.getDataTable().ajax.reload();
            }
        });
        $(document).on('change', '#statusfilter', function(e) {
            e.preventDefault();
            var action = $('#statusfilter').val();
            if (action != "") {
                grid2.setAjaxParam("customActionType", "group_action");
                grid2.setAjaxParam("customActionName", action);
                grid2.setAjaxParam("id", grid2.getSelectedRows());
                grid2.getDataTable().ajax.reload();
            } else {
                grid2.setAjaxParam("customActionType", "group_action");
                grid2.setAjaxParam("customActionName", action);
                grid2.setAjaxParam("id", grid2.getSelectedRows());
            }
        });
        $(document).on('change', '#bannerFilterType', function(e) {
            e.preventDefault();
            var action = $('#bannerFilterType').val();
            if (action != "") {
                grid2.setAjaxParam("customActionType", "group_action");
                grid2.setAjaxParam("bannerFilterType", action);
                grid2.setAjaxParam("id", grid2.getSelectedRows());
                grid2.getDataTable().ajax.reload();
            } else {
                grid2.setAjaxParam("customActionType", "group_action");
                grid2.setAjaxParam("customActionName", action);
                grid2.setAjaxParam("id", grid2.getSelectedRows());
            }
        });
        $(document).on('change', '#pageFilter', function(e) {
            e.preventDefault();
            var action = $(this).val();
            if (action != "") {
                grid2.setAjaxParam("customActionType", "group_action");
                grid2.setAjaxParam("pageFilter", action);
                grid2.setAjaxParam("id", grid2.getSelectedRows());
                grid2.getDataTable().ajax.reload();
            } else {
                grid2.setAjaxParam("customActionType", "group_action");
                grid2.setAjaxParam("pageFilter", action);
                grid2.setAjaxParam("id", grid2.getSelectedRows());
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
                data: { alias: alias, val: val },
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
        $(document).on('click', '.defaultBanner', function(e) {
            e.preventDefault();
            var controller = $(this).data('controller');
            var alias = $(this).data('alias');
            var val = $(this).data('value');
            var url = site_url + '/' + controller + '/makeDefault';
            $.ajax({
                url: url,
                data: { alias: alias, val: val },
                type: "POST",
                dataType: "HTML",
                success: function(data) {
                    grid2.getDataTable().ajax.reload();
                },
                error: function() {
                    console.log('error!');
                }
            });
        });

        $('a[data-toggle="tab"][id="MenuItem3"]').on('shown.bs.tab', function(e) {
            $("#hidefilter").show();
            e.preventDefault();
            grid2.setAjaxParam("id", grid2.getSelectedRows());
            grid2.getDataTable().ajax.reload();
        });

        if (!showChecker) {
            grid2.getDataTable().column(0).visible(false);
        } else {
            grid2.getDataTable().column(0).visible(true);
        }

        grid2.setAjaxParam("customActionType", "group_action");
        grid2.clearAjaxParams();
        grid2.getDataTable().columns().iterator('column', function(ctx, idx) {
            $(grid2.getDataTable().column(idx).header()).append('<span class="sort-icon"/>');
        });
    }
    return {
        //main function to initiate the module
        init: function() {
            handleRecords2();
        }
    };
}();
var grid3Rows = 0;
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
            onDataLoad: function(grid2) {
                $('.make-switch').bootstrapSwitch();
            },
            loadingMessage: 'Loading...',
            dataTable: {
                "deferRender": true,
                "stateSave": true, // save datatable state(pagination, sort, etc) in cookie.
                "lengthMenu": [
                    [10, 20, 50, 100],
                    [10, 20, 50, 100] // change per page values here
                ],
                "pageLength": 100, // default record count per page
                //Code for sorting 
                "serverSide": true,
                "columns": [
                    { "data": 0, className: 'td_checker', 'bSortable': false },
                    { "data": 1, 'name': 'varTitle', className: 'text-left Banners_title_T_1 mob-show_div' },
                    { "data": 2, 'bSortable': false, className: 'text-center Banners_image_T_2' },
                    { "data": 3, 'name': 'varBannerType', className: 'text-center Banners_bannertype_T_3' },
                    { "data": 4, 'bSortable': false, className: 'text-center Banners_page_T_4' },
                    {
                        "data": 5,
                        className: 'text-center Banners_sdate_T_5',
                        "name": 'dtDateTime',
                        "bSortable": true
                    },
                    { "data": 6, 'bSortable': false, className: 'text-right Banners_dactions_T_9 last_td_action mob-show_div' },
                    { "data": 7, 'bSortable': false }
                ],
                "columnDefs": [{
                    "targets": [7],
                    "visible": false,
                    "searchable": false
                }],
                "ajax": {
                    "url": window.site_url + "/powerpanel/banners/get_list_trash", // ajax source
                },
                'fnCreatedRow': function(nRow, aData, iDataIndex) {
                    $(nRow).attr('data-order', aData[6]);
                },
                "order": [
                    [5, "desc"]
                ]
            }
        });
        $('#datatable_ajax3 tbody').on('click', '.moveDwn', function() {
            var order = $(this).data('order');
            exOrder = order + 1;
            reorder(order, exOrder);
        });
        $('#datatable_ajax3 tbody').on('click', '.moveUp', function() {
            var order = $(this).data('order');
            exOrder = order - 1;
            reorder(order, exOrder);
        });
        /*********/
        $(document).on('change', '#bannerFilter', function(e) {
            e.preventDefault();
            var action = $(this).val();
            if (action != "") {
                grid3.setAjaxParam("customActionType", "group_action");
                grid3.setAjaxParam("bannerFilter", action);
                grid3.setAjaxParam("id", grid3.getSelectedRows());
                grid3.getDataTable().ajax.reload();
            } else {
                grid3.setAjaxParam("customActionType", "group_action");
                grid3.setAjaxParam("bannerFilter", action);
                grid3.setAjaxParam("id", grid3.getSelectedRows());
            }
        });
        /*********/
        //This code for search filter
        $(document).on('keyup', '#searchfilter', function(e) {
            e.preventDefault();
            var action = $('#searchfilter').val();
            if (action.length >= 2) {
                $.cookie('bannerSearch', action);
                grid3.setAjaxParam("customActionType", "group_action");
                grid3.setAjaxParam("searchValue", action);
                grid3.setAjaxParam("id", grid3.getSelectedRows());
                grid3.getDataTable().ajax.reload();
            } else if (action.length < 1) {
                $.removeCookie('bannerSearch');
                grid3.setAjaxParam("customActionType", "group_action");
                grid3.setAjaxParam("searchValue", action);
                grid3.setAjaxParam("id", grid3.getSelectedRows());
                grid3.getDataTable().ajax.reload();
            }
        });
        $(document).on('change', '#statusfilter', function(e) {
            e.preventDefault();
            var action = $('#statusfilter').val();
            if (action != "") {
                grid3.setAjaxParam("customActionType", "group_action");
                grid3.setAjaxParam("customActionName", action);
                grid3.setAjaxParam("id", grid3.getSelectedRows());
                grid3.getDataTable().ajax.reload();
            } else {
                grid3.setAjaxParam("customActionType", "group_action");
                grid3.setAjaxParam("customActionName", action);
                grid3.setAjaxParam("id", grid3.getSelectedRows());
            }
        });
        $(document).on('change', '#bannerFilterType', function(e) {
            e.preventDefault();
            var action = $('#bannerFilterType').val();
            if (action != "") {
                grid3.setAjaxParam("customActionType", "group_action");
                grid3.setAjaxParam("bannerFilterType", action);
                grid3.setAjaxParam("id", grid3.getSelectedRows());
                grid3.getDataTable().ajax.reload();
            } else {
                grid3.setAjaxParam("customActionType", "group_action");
                grid3.setAjaxParam("customActionName", action);
                grid3.setAjaxParam("id", grid3.getSelectedRows());
            }
        });
        $(document).on('change', '#pageFilter', function(e) {
            e.preventDefault();
            var action = $(this).val();
            if (action != "") {
                grid3.setAjaxParam("customActionType", "group_action");
                grid3.setAjaxParam("pageFilter", action);
                grid3.setAjaxParam("id", grid3.getSelectedRows());
                grid3.getDataTable().ajax.reload();
            } else {
                grid3.setAjaxParam("customActionType", "group_action");
                grid3.setAjaxParam("pageFilter", action);
                grid3.setAjaxParam("id", grid3.getSelectedRows());
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
                data: { alias: alias, val: val },
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
        $(document).on('click', '.defaultBanner', function(e) {
            e.preventDefault();
            var controller = $(this).data('controller');
            var alias = $(this).data('alias');
            var val = $(this).data('value');
            var url = site_url + '/' + controller + '/makeDefault';
            $.ajax({
                url: url,
                data: { alias: alias, val: val },
                type: "POST",
                dataType: "HTML",
                success: function(data) {
                    grid3.getDataTable().ajax.reload();
                },
                error: function() {
                    console.log('error!');
                }
            });
        });

        $('a[data-toggle="tab"][id="MenuItem4"]').on('shown.bs.tab', function(e) {
            $("#hidefilter").show();
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
            handleRecords3();
        }
    };
}();
var grid4Rows = 0;
var grid4 = '';
var TableDatatablesAjax4 = function() {
    var handleRecords4 = function() {
        grid4 = new Datatable();
        grid4.init({
            src: $("#datatable_ajax4"),
            onSuccess: function(grid4, response) {
                grid4Rows = response.recordsTotal;
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
                $('.make-switch').bootstrapSwitch();
            },
            loadingMessage: 'Loading...',
            dataTable: {
                "deferRender": true,
                "stateSave": true, // save datatable state(pagination, sort, etc) in cookie.
                "lengthMenu": [
                    [10, 20, 50, 100],
                    [10, 20, 50, 100] // change per page values here
                ],
                "pageLength": 100, // default record count per page
                //Code for sorting 
                "serverSide": true,
                "columns": [
                    { "data": 0, className: 'td_checker', 'bSortable': false },
                    {
                        "data": 1,
                        'class': 'text-center mob-show_div',
                        'bSortable': false
                    },
                    { "data": 2, 'name': 'varTitle', className: 'text-left Banners_title_F_2 mob-show_div' },
                    { "data": 3, 'bSortable': false, className: 'text-center Banners_image_F_3' },
                    { "data": 4, 'name': 'varBannerType', className: 'text-center Banners_bannertype_F_4' },
                    { "data": 5, 'bSortable': false, className: 'text-center Banners_page_F_5' },
                    {
                        "data": 6,
                        className: 'text-center Banners_sdate_F_6',
                        "name": 'dtDateTime',
                        "bSortable": true
                    },
                    { "data": 7, 'bSortable': false, className: 'text-right Banners_dactions_F_9 last_td_action mob-show_div' },
                    { "data": 8, 'bSortable': false }
                ],
                "columnDefs": [{
                    "targets": [8],
                    "visible": false,
                    "searchable": false
                }],
                "ajax": {
                    "url": window.site_url + "/powerpanel/banners/get_list_favorite", // ajax source
                },
                'fnCreatedRow': function(nRow, aData, iDataIndex) {
                    $(nRow).attr('data-order', aData[7]);
                },
                "order": [
                    [6, "desc"]
                ]
            }
        });
        $('#datatable_ajax4 tbody').on('click', '.moveDwn', function() {
            var order = $(this).data('order');
            exOrder = order + 1;
            reorder(order, exOrder);
        });
        $('#datatable_ajax4 tbody').on('click', '.moveUp', function() {
            var order = $(this).data('order');
            exOrder = order - 1;
            reorder(order, exOrder);
        });
        /*********/
        $(document).on('change', '#bannerFilter', function(e) {
            e.preventDefault();
            var action = $(this).val();
            if (action != "") {
                grid4.setAjaxParam("customActionType", "group_action");
                grid4.setAjaxParam("bannerFilter", action);
                grid4.setAjaxParam("id", grid4.getSelectedRows());
                grid4.getDataTable().ajax.reload();
            } else {
                grid4.setAjaxParam("customActionType", "group_action");
                grid4.setAjaxParam("bannerFilter", action);
                grid4.setAjaxParam("id", grid4.getSelectedRows());
            }
        });
        /*********/
        //This code for search filter
        $(document).on('keyup', '#searchfilter', function(e) {
            e.preventDefault();
            var action = $('#searchfilter').val();
            if (action.length >= 2) {
                $.cookie('bannerSearch', action);
                grid4.setAjaxParam("customActionType", "group_action");
                grid4.setAjaxParam("searchValue", action);
                grid4.setAjaxParam("id", grid4.getSelectedRows());
                grid4.getDataTable().ajax.reload();
            } else if (action.length < 1) {
                $.removeCookie('bannerSearch');
                grid4.setAjaxParam("customActionType", "group_action");
                grid4.setAjaxParam("searchValue", action);
                grid4.setAjaxParam("id", grid4.getSelectedRows());
                grid4.getDataTable().ajax.reload();
            }
        });
        $(document).on('change', '#statusfilter', function(e) {
            e.preventDefault();
            var action = $('#statusfilter').val();
            if (action != "") {
                grid4.setAjaxParam("customActionType", "group_action");
                grid4.setAjaxParam("customActionName", action);
                grid4.setAjaxParam("id", grid4.getSelectedRows());
                grid4.getDataTable().ajax.reload();
            } else {
                grid4.setAjaxParam("customActionType", "group_action");
                grid4.setAjaxParam("customActionName", action);
                grid4.setAjaxParam("id", grid4.getSelectedRows());
            }
        });
        $(document).on('change', '#bannerFilterType', function(e) {
            e.preventDefault();
            var action = $('#bannerFilterType').val();
            if (action != "") {
                grid4.setAjaxParam("customActionType", "group_action");
                grid4.setAjaxParam("bannerFilterType", action);
                grid4.setAjaxParam("id", grid4.getSelectedRows());
                grid4.getDataTable().ajax.reload();
            } else {
                grid4.setAjaxParam("customActionType", "group_action");
                grid4.setAjaxParam("customActionName", action);
                grid4.setAjaxParam("id", grid4.getSelectedRows());
            }
        });
        $(document).on('change', '#pageFilter', function(e) {
            e.preventDefault();
            var action = $(this).val();
            if (action != "") {
                grid4.setAjaxParam("customActionType", "group_action");
                grid4.setAjaxParam("pageFilter", action);
                grid4.setAjaxParam("id", grid4.getSelectedRows());
                grid4.getDataTable().ajax.reload();
            } else {
                grid4.setAjaxParam("customActionType", "group_action");
                grid4.setAjaxParam("pageFilter", action);
                grid4.setAjaxParam("id", grid4.getSelectedRows());
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
                data: { alias: alias, val: val },
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
        $(document).on('click', '.defaultBanner', function(e) {
            e.preventDefault();
            var controller = $(this).data('controller');
            var alias = $(this).data('alias');
            var val = $(this).data('value');
            var url = site_url + '/' + controller + '/makeDefault';
            $.ajax({
                url: url,
                data: { alias: alias, val: val },
                type: "POST",
                dataType: "HTML",
                success: function(data) {
                    grid4.getDataTable().ajax.reload();
                },
                error: function() {
                    console.log('error!');
                }
            });
        });

        $('a[data-toggle="tab"][id="MenuItem5"]').on('shown.bs.tab', function(e) {
            $("#hidefilter").show();
            e.preventDefault();
            grid4.setAjaxParam("id", grid4.getSelectedRows());
            grid4.getDataTable().ajax.reload();
        });

        if (!showChecker) {
            grid4.getDataTable().column(0).visible(false);
        } else {
            grid4.getDataTable().column(0).visible(true);
        }

        grid4.setAjaxParam("customActionType", "group_action");
        grid4.clearAjaxParams();
        grid4.getDataTable().columns().iterator('column', function(ctx, idx) {
            $(grid4.getDataTable().column(idx).header()).append('<span class="sort-icon"/>');
        });
    }
    return {
        //main function to initiate the module
        init: function() {
            handleRecords4();
        }
    };
}();
var grid5Rows = 0;
var grid5 = '';
var TableDatatablesAjax5 = function() {
    var handleRecords5 = function() {
        grid5 = new Datatable();
        grid5.init({
            src: $("#banners_datatable_ajax5"),
            onSuccess: function(grid5, response) {
                grid5Rows = response.recordsTotal;
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
            onError: function(grid5) {
                // execute some code on network or other general error  
            },
            onDataLoad: function(grid5) {
                $('.make-switch').bootstrapSwitch();
            },
            loadingMessage: 'Loading...',
            dataTable: {
                "deferRender": true,
                "stateSave": true, // save datatable state(pagination, sort, etc) in cookie.
                "lengthMenu": [
                    [10, 20, 50, 100],
                    [10, 20, 50, 100] // change per page values here
                ],
                "pageLength": 100, // default record count per page
                //Code for sorting 
                "serverSide": true,
                "columns": [
                    { "data": 0, className: 'td_checker', 'bSortable': false },
                    {
                        "data": 1,
                        'class': 'text-center mob-show_div',
                        'bSortable': false
                    },
                    { "data": 2, 'name': 'varTitle', className: 'text-left Banners_title_R_2 mob-show_div' },
                    { "data": 3, 'bSortable': false, className: 'text-center Banners_image_R_3' },
                    { "data": 4, 'name': 'varBannerType', className: 'text-center Banners_bannertype_R_4' },
                    { "data": 5, 'bSortable': false, className: 'text-center Banners_page_R_5' },
                    {
                        "data": 6,
                        className: 'text-center Banners_sdate_R_6',
                        "name": 'dtDateTime',
                        "bSortable": true
                    }, {
                        "data": 7,
                        className: 'text-center Banners_edate_R_7',
                        "name": 'dtEndDateTime',
                        "bSortable": true
                    },
                    { "data": 8, 'bSortable': false, className: 'text-center Banners_rotatetime_R_8', 'name': 'varRotateTime' },
                    { "data": 9, 'bSortable': false, className: 'text-right Banners_dactions_R_10 last_td_action mob-show_div' },
                    { "data": 10, 'bSortable': false }
                ],
                "columnDefs": [{
                    "targets": [10],
                    "visible": false,
                    "searchable": false
                }],
                "ajax": {
                    "url": window.site_url + "/powerpanel/banners/get_list_archive", // ajax source
                },
                'fnCreatedRow': function(nRow, aData, iDataIndex) {
                    $(nRow).attr('data-order', aData[8]);
                },
                "order": [
                    [6, "desc"]
                ]
            }
        });
        $('#banners_datatable_ajax5 tbody').on('click', '.moveDwn', function() {
            var order = $(this).data('order');
            exOrder = order + 1;
            reorder(order, exOrder);
        });
        $('#banners_datatable_ajax5 tbody').on('click', '.moveUp', function() {
            var order = $(this).data('order');
            exOrder = order - 1;
            reorder(order, exOrder);
        });
        /*********/
        $(document).on('change', '#bannerFilter', function(e) {
            e.preventDefault();
            var action = $(this).val();
            if (action != "") {
                grid5.setAjaxParam("customActionType", "group_action");
                grid5.setAjaxParam("bannerFilter", action);
                grid5.setAjaxParam("id", grid5.getSelectedRows());
                grid5.getDataTable().ajax.reload();
            } else {
                grid5.setAjaxParam("customActionType", "group_action");
                grid5.setAjaxParam("bannerFilter", action);
                grid5.setAjaxParam("id", grid5.getSelectedRows());
            }
        });
        /*********/
        //This code for search filter
        $(document).on('keyup', '#searchfilter', function(e) {
            e.preventDefault();
            var action = $('#searchfilter').val();
            if (action.length >= 2) {
                $.cookie('bannerSearch', action);
                grid5.setAjaxParam("customActionType", "group_action");
                grid5.setAjaxParam("searchValue", action);
                grid5.setAjaxParam("id", grid5.getSelectedRows());
                grid5.getDataTable().ajax.reload();
            } else if (action.length < 1) {
                $.removeCookie('bannerSearch');
                grid5.setAjaxParam("customActionType", "group_action");
                grid5.setAjaxParam("searchValue", action);
                grid5.setAjaxParam("id", grid5.getSelectedRows());
                grid5.getDataTable().ajax.reload();
            }
        });
        $(document).on('change', '#statusfilter', function(e) {
            e.preventDefault();
            var action = $('#statusfilter').val();
            if (action != "") {
                grid5.setAjaxParam("customActionType", "group_action");
                grid5.setAjaxParam("customActionName", action);
                grid5.setAjaxParam("id", grid5.getSelectedRows());
                grid5.getDataTable().ajax.reload();
            } else {
                grid5.setAjaxParam("customActionType", "group_action");
                grid5.setAjaxParam("customActionName", action);
                grid5.setAjaxParam("id", grid5.getSelectedRows());
            }
        });
        $(document).on('change', '#bannerFilterType', function(e) {
            e.preventDefault();
            var action = $('#bannerFilterType').val();
            if (action != "") {
                grid5.setAjaxParam("customActionType", "group_action");
                grid5.setAjaxParam("bannerFilterType", action);
                grid5.setAjaxParam("id", grid5.getSelectedRows());
                grid5.getDataTable().ajax.reload();
            } else {
                grid5.setAjaxParam("customActionType", "group_action");
                grid5.setAjaxParam("customActionName", action);
                grid5.setAjaxParam("id", grid5.getSelectedRows());
            }
        });
        $(document).on('change', '#pageFilter', function(e) {
            e.preventDefault();
            var action = $(this).val();
            if (action != "") {
                grid5.setAjaxParam("customActionType", "group_action");
                grid5.setAjaxParam("pageFilter", action);
                grid5.setAjaxParam("id", grid5.getSelectedRows());
                grid5.getDataTable().ajax.reload();
            } else {
                grid5.setAjaxParam("customActionType", "group_action");
                grid5.setAjaxParam("pageFilter", action);
                grid5.setAjaxParam("id", grid5.getSelectedRows());
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
                data: { alias: alias, val: val },
                type: "POST",
                dataType: "HTML",
                success: function(data) {
                    grid5.getDataTable().ajax.reload(null, false);
                },
                error: function() {
                    console.log('error!');
                }
            });
        });
        $(document).on('click', '.defaultBanner', function(e) {
            e.preventDefault();
            var controller = $(this).data('controller');
            var alias = $(this).data('alias');
            var val = $(this).data('value');
            var url = site_url + '/' + controller + '/makeDefault';
            $.ajax({
                url: url,
                data: { alias: alias, val: val },
                type: "POST",
                dataType: "HTML",
                success: function(data) {
                    grid5.getDataTable().ajax.reload();
                },
                error: function() {
                    console.log('error!');
                }
            });
        });

        $('a[data-toggle="tab"][id="MenuItem6"]').on('shown.bs.tab', function(e) {
            $("#hidefilter").show();
            e.preventDefault();
            grid5.setAjaxParam("id", grid5.getSelectedRows());
            grid5.getDataTable().ajax.reload();
        });

        if (!showChecker) {
            grid5.getDataTable().column(0).visible(false);
        } else {
            grid5.getDataTable().column(0).visible(true);
        }

        $(document).on("click", '#RgrpChkBox .checkbox_R', function() {
            if ($(this).prop("checked") == true) {
                var datatable_R = $(this).attr("name").split("_");
                grid5.getDataTable().column(datatable_R[3]).visible(true);
                Cookies.set($(this).attr("name"), 'Y');
                grid5.getDataTable().ajax.reload();
            } else {
                var datatable_R = $(this).attr("name").split("_");
                grid5.getDataTable().column(datatable_R[3]).visible(false);
                Cookies.set($(this).attr("name"), 'N');
                grid5.getDataTable().ajax.reload();
            }
        });
        grid5.setAjaxParam("customActionType", "group_action");
        grid5.clearAjaxParams();
        grid5.getDataTable().columns().iterator('column', function(ctx, idx) {
            $(grid5.getDataTable().column(idx).header()).append('<span class="sort-icon"/>');
        });
    }
    return {
        //main function to initiate the module
        init: function() {
            handleRecords5();
        }
    };
}();
$(window).on('load', function() {
    if ($.cookie('bannerSearch')) {
        $('#searchfilter').val($.cookie('bannerSearch'));
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
    $("#hidefilter").show();

});
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
    }
});
$('a[data-toggle="tab"][id="MenuItem6"]').on('shown.bs.tab', function(e) {
    $("#hidefilter").hide();
    if (!$.fn.DataTable.isDataTable('#banners_datatable_ajax5')) {
        TableDatatablesAjax5.init();
    }
});

function reorder(curOrder, excOrder) {
    var ajaxurl = site_url + '/powerpanel/banners/reorder';
    $.ajax({
        url: ajaxurl,
        data: { order: curOrder, exOrder: excOrder },
        type: "POST",
        dataType: "HTML",
        success: function(data) {},
        complete: function() {
            grid.getDataTable().ajax.reload(null, false);
            grid1.getDataTable().ajax.reload(null, false);
        },
        error: function() {
            console.log('error!');
        }
    });
}