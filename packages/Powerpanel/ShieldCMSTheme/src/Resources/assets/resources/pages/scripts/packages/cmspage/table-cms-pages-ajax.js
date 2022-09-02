var grid = '';
var TableDatatablesAjax = function() {
    var handleRecords = function() {
        grid = new Datatable();
        grid.init({
            src: $("#datatable_ajax"),
            onSuccess: function(grid, response) {
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
                $('[data-bs-toggle="tooltip"]').tooltip();
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
                    'class': 'text-center',
                    'bSortable': false
                }, {
                    "data": 1,
                    'class': 'text-center mob-show_div',
                    'bSortable': false
                }, {
                    "data": 2,
                    'class': 'text-left Pages_title_P_2 mob-show_div',
                    'name': 'varTitle'
                }, {
                    "data": 3,
                    'class': 'text-center Pages_module_P_3',
                    'bSortable': false
                }, {
                    "data": 4,
                    "name": 'dtDateTime',
                    className: 'text-center Pages_sdate_P_4'
                }, {
                    "data": 5,
                    "name": 'dtEndDateTime',
                    className: 'text-center Pages_edate_P_5'
                }, {
                    "data": 6,
                    'class': 'text-center Pages_hits_P_6',
                    'bSortable': false
                }, {
                    "data": 7,
                    'class': 'text-center publish_switch Pages_publish_P_7',
                    'bSortable': false
                }, {
                    "data": 8,
                    'class': 'text-center Pages_mdate_P_8',
                    'bSortable': true,
                    'name': 'updated_at'
                }, {
                    "data": 9,
                    'class': 'text-right Pages_dactions_P_9 last_td_action mob-show_div',
                    'bSortable': false
                }, ],
                "ajax": {
                    "url": window.site_url + "/powerpanel/pages/get_list", // ajax source
                },
                "order": [
                        [4, "desc"]
                    ] // set first column as a default sort by asc
            }
        });
        //This code for search filter
        $(document).on('keyup', '#searchfilter', function(e) {
            e.preventDefault();
            var action = $('#searchfilter').val();
            if (action.length >= 2) {
                $.cookie('cmsPagesSearch', action);
                grid.setAjaxParam("customActionType", "group_action");
                grid.setAjaxParam("searchValue", action);
                grid.setAjaxParam("id", grid.getSelectedRows());
                grid.getDataTable().ajax.reload();
            } else if (action.length < 1) {
                $.removeCookie('cmsPagesSearch');
                grid.setAjaxParam("customActionType", "group_action");
                grid.setAjaxParam("searchValue", action);
                grid.setAjaxParam("id", grid.getSelectedRows());
                grid.getDataTable().ajax.reload();
            }
        });
        //This code for email type filter
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
        generateHeadfilterEvents(grid);
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
        $('a[data-toggle="tab"][id="MenuItem1"]').on('shown.bs.tab', function(e) {
            $("#hidefilter").show();
            e.preventDefault();
            grid.setAjaxParam("id", grid.getSelectedRows());
            grid.setAjaxParam("customFilterIdentity", "");
            grid.getDataTable().ajax.reload();
        });
        if (!showChecker) {
            grid.getDataTable().column(7).visible(false);
        } else {
            if ($.cookie('Pages_publish_P_7') == 'Y') {
                grid.getDataTable().column(7).visible(true);
            }
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
var grid1 = '';
var TableDatatablesAjax1 = function() {
    var handleRecords1 = function() {
        grid1 = new Datatable();
        grid1.init({
            src: $("#datatable_ajax_approved"),
            onSuccess: function(grid1, response) {
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
                $('[data-bs-toggle="tooltip"]').tooltip();
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
                "columns": [
                    //                    {"data": 0, 'class': 'text-center', 'bSortable': false},
                    {
                        "data": 0,
                        'class': 'text-center mob-show_div',
                        'bSortable': false
                    }, {
                        "data": 1,
                        'class': 'text-left Pages_title_A_2 mob-show_div',
                        'name': 'varTitle',
                        'bSortable': true
                    }, {
                        "data": 2,
                        'class': 'text-center Pages_module_A_3',
                        'bSortable': false
                    }, {
                        "data": 3,
                        "name": 'dtDateTime',
                        className: 'text-center Pages_sdate_A_4'
                    }, {
                        "data": 4,
                        "name": 'dtEndDateTime',
                        className: 'text-center Pages_edate_A_5'
                    }, {
                        "data": 5,
                        'class': 'text-center Pages_hits_A_6',
                        'bSortable': false
                    }, {
                        "data": 6,
                        'class': 'text-center Pages_mdate_A_8',
                        'bSortable': true,
                        'name': 'updated_at'
                    }, {
                        "data": 7,
                        'class': 'text-right Pages_dactions_A_9 last_td_action mob-show_div',
                        'bSortable': false
                    },
                ],
                "ajax": {
                    "url": window.site_url + "/powerpanel/pages/get_list_New", // ajax source
                },
                "order": [
                        [3, "desc"]
                    ] // set first column as a default sort by asc
            }
        });
        //This code for search filter
        $(document).on('keyup', '#searchfilter', function(e) {
            e.preventDefault();
            var action = $('#searchfilter').val();
            if (action.length >= 2) {
                $.cookie('cmsPagesSearch', action);
                grid1.setAjaxParam("customActionType", "group_action");
                grid1.setAjaxParam("searchValue", action);
                grid1.setAjaxParam("id", grid1.getSelectedRows());
                grid1.getDataTable().ajax.reload();
            } else if (action.length < 1) {
                $.removeCookie('cmsPagesSearch');
                grid1.setAjaxParam("customActionType", "group_action");
                grid1.setAjaxParam("searchValue", action);
                grid1.setAjaxParam("id", grid1.getSelectedRows());
                grid1.getDataTable().ajax.reload();
            }
        });
        //This code for email type filter
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
        generateHeadfilterEvents(grid1);
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
                    grid1.getDataTable().ajax.reload(null, false);
                },
                error: function() {
                    console.log('error!');
                }
            });
        });
        grid1.setAjaxParam("customActionType", "group_action");
        grid1.clearAjaxParams();
        grid1.getDataTable().columns().iterator('column', function(ctx, idx) {
            $(grid1.getDataTable().column(idx).header()).append('<span class="sort-icon"/>');
        });

        $('a[data-toggle="tab"][id="MenuItem2"]').on('shown.bs.tab', function(e) {
            $("#hidefilter").hide();
            e.preventDefault();
            grid1.setAjaxParam("id", grid1.getSelectedRows());
            grid1.setAjaxParam("customFilterIdentity", "");
            grid1.getDataTable().ajax.reload();
        });
    }
    return {
        //main function to initiate the module
        init: function() {
            handleRecords1();
        }
    };
}();
var grid2 = '';
var TableDatatablesAjax2 = function() {
    var handleRecords2 = function() {
        grid2 = new Datatable();
        grid2.init({
            src: $("#datatable_ajax2"),
            onSuccess: function(grid2, response) {
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
                $('[data-bs-toggle="tooltip"]').tooltip();
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
                    'class': 'text-center',
                    'bSortable': false
                }, {
                    "data": 1,
                    'class': 'text-center mob-show_div',
                    'bSortable': false
                }, {
                    "data": 2,
                    'class': 'text-left Pages_title_D_2 mob-show_div',
                    'name': 'varTitle'
                }, {
                    "data": 3,
                    'class': 'text-center Pages_module_D_3',
                    'bSortable': false
                }, {
                    "data": 4,
                    "name": 'dtDateTime',
                    className: 'text-center Pages_sdate_D_4'
                }, {
                    "data": 5,
                    "name": 'dtEndDateTime',
                    className: 'text-center Pages_edate_D_5'
                }, {
                    "data": 6,
                    'class': 'text-center publish_switch Pages_publish_D_7',
                    'bSortable': false
                }, {
                    "data": 7,
                    'class': 'text-center Pages_mdate_D_8',
                    'bSortable': true,
                    'name': 'updated_at'
                }, {
                    "data": 8,
                    'class': 'text-right Pages_dactions_D_9 last_td_action mob-show_div',
                    'bSortable': false
                }, ],
                "ajax": {
                    "url": window.site_url + "/powerpanel/pages/get_list_draft", // ajax source
                },
                "order": [
                        [4, "desc"]
                    ] // set first column as a default sort by asc
            }
        });
        //This code for search filter
        $(document).on('keyup', '#searchfilter', function(e) {
            e.preventDefault();
            var action = $('#searchfilter').val();
            if (action.length >= 2) {
                $.cookie('cmsPagesSearch', action);
                grid2.setAjaxParam("customActionType", "group_action");
                grid2.setAjaxParam("searchValue", action);
                grid2.setAjaxParam("id", grid2.getSelectedRows());
                grid2.getDataTable().ajax.reload();
            } else if (action.length < 1) {
                $.removeCookie('cmsPagesSearch');
                grid2.setAjaxParam("customActionType", "group_action");
                grid2.setAjaxParam("searchValue", action);
                grid2.setAjaxParam("id", grid2.getSelectedRows());
                grid2.getDataTable().ajax.reload();
            }
        });
        //This code for email type filter
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
        generateHeadfilterEvents(grid2);
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
                    grid2.getDataTable().ajax.reload(null, false);
                },
                error: function() {
                    console.log('error!');
                }
            });
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

        $('a[data-toggle="tab"][id="MenuItem3"]').on('shown.bs.tab', function(e) {
            $("#hidefilter").hide();
            e.preventDefault();
            grid2.setAjaxParam("id", grid2.getSelectedRows());
            grid2.setAjaxParam("customFilterIdentity", "");
            grid2.getDataTable().ajax.reload();
        });

    }
    return {
        //main function to initiate the module
        init: function() {
            handleRecords2();
        }
    };
}();

var grid3 = '';
var TableDatatablesAjax3 = function() {
    var handleRecords3 = function() {
        grid3 = new Datatable();
        grid3.init({
            src: $("#datatable_ajax3"),
            onSuccess: function(grid3, response) {
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
                $('[data-bs-toggle="tooltip"]').tooltip();
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
                    'class': 'text-center',
                    'bSortable': false
                }, {
                    "data": 1,
                    'class': 'text-left Pages_title_T_2 mob-show_div',
                    'name': 'varTitle'
                }, {
                    "data": 2,
                    'class': 'text-center Pages_module_T_3',
                    'bSortable': false
                }, {
                    "data": 3,
                    "name": 'dtDateTime',
                    className: 'text-center Pages_sdate_T_4'
                }, {
                    "data": 4,
                    "name": 'dtEndDateTime',
                    className: 'text-center Pages_edate_T_5'
                }, {
                    "data": 5,
                    'class': 'text-center Pages_hits_T_6',
                    'bSortable': false
                }, {
                    "data": 6,
                    'class': 'text-center Pages_mdate_T_8',
                    'bSortable': true,
                    'name': 'updated_at'
                }, {
                    "data": 7,
                    'class': 'text-right Pages_dactions_T_9 last_td_action mob-show_div',
                    'bSortable': false,
                }, ],
                "ajax": {
                    "url": window.site_url + "/powerpanel/pages/get_list_trash", // ajax source
                },
                "order": [
                        [4, "desc"]
                    ] // set first column as a default sort by asc
            }
        });
        //This code for search filter
        $(document).on('keyup', '#searchfilter', function(e) {
            e.preventDefault();
            var action = $('#searchfilter').val();
            if (action.length >= 2) {
                $.cookie('cmsPagesSearch', action);
                grid3.setAjaxParam("customActionType", "group_action");
                grid3.setAjaxParam("searchValue", action);
                grid3.setAjaxParam("id", grid3.getSelectedRows());
                grid3.getDataTable().ajax.reload();
            } else if (action.length < 1) {
                $.removeCookie('cmsPagesSearch');
                grid3.setAjaxParam("customActionType", "group_action");
                grid3.setAjaxParam("searchValue", action);
                grid3.setAjaxParam("id", grid3.getSelectedRows());
                grid3.getDataTable().ajax.reload();
            }
        });
        //This code for email type filter
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
        generateHeadfilterEvents(grid3);
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
                    grid3.getDataTable().ajax.reload(null, false);
                },
                error: function() {
                    console.log('error!');
                }
            });
        });

        grid3.setAjaxParam("customActionType", "group_action");
        grid3.clearAjaxParams();
        grid3.getDataTable().columns().iterator('column', function(ctx, idx) {
            $(grid3.getDataTable().column(idx).header()).append('<span class="sort-icon"/>');
        });
        $('a[data-toggle="tab"][id="MenuItem4"]').on('shown.bs.tab', function(e) {
            $("#hidefilter").hide();
            e.preventDefault();
            grid3.setAjaxParam("id", grid3.getSelectedRows());
            grid3.setAjaxParam("customFilterIdentity", "");
            grid3.getDataTable().ajax.reload();
        });
    }
    return {
        //main function to initiate the module
        init: function() {
            handleRecords3();
        }
    };
}();

var grid4 = '';
var TableDatatablesAjax4 = function() {
    var handleRecords4 = function() {
        grid4 = new Datatable();
        grid4.init({
            src: $("#datatable_ajax4"),
            onSuccess: function(grid4, response) {
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
                $('[data-bs-toggle="tooltip"]').tooltip();
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
                    'class': 'text-center',
                    'bSortable': false
                }, {
                    "data": 1,
                    'class': 'text-center mob-show_div',
                    'bSortable': false
                }, {
                    "data": 2,
                    'class': 'text-left Pages_title_F_2 mob-show_div',
                    'name': 'varTitle'
                }, {
                    "data": 3,
                    'class': 'text-center Pages_module_F_3',
                    'bSortable': false
                }, {
                    "data": 4,
                    "name": 'dtDateTime',
                    className: 'text-center Pages_sdate_F_4'
                }, {
                    "data": 5,
                    "name": 'dtEndDateTime',
                    className: 'text-center Pages_edate_F_5'
                }, {
                    "data": 6,
                    'class': 'text-center Pages_hits_F_6',
                    'bSortable': false
                }, {
                    "data": 7,
                    'class': 'text-center Pages_mdate_F_8',
                    'bSortable': true,
                    'name': 'updated_at'
                }, {
                    "data": 8,
                    'class': 'text-right Pages_dactions_F_9 last_td_action mob-show_div',
                    'bSortable': false,
                }, ],
                "ajax": {
                    "url": window.site_url + "/powerpanel/pages/get_list_favorite", // ajax source
                },
                "order": [
                        [4, "desc"]
                    ] // set first column as a default sort by asc
            }
        });

        //This code for search filter
        $(document).on('keyup', '#searchfilter', function(e) {
            e.preventDefault();
            var action = $('#searchfilter').val();
            if (action.length >= 2) {
                $.cookie('cmsPagesSearch', action);
                grid4.setAjaxParam("customActionType", "group_action");
                grid4.setAjaxParam("searchValue", action);
                grid4.setAjaxParam("id", grid4.getSelectedRows());
                grid4.getDataTable().ajax.reload();
            } else if (action.length < 1) {
                $.removeCookie('cmsPagesSearch');
                grid4.setAjaxParam("customActionType", "group_action");
                grid4.setAjaxParam("searchValue", action);
                grid4.setAjaxParam("id", grid4.getSelectedRows());
                grid4.getDataTable().ajax.reload();
            }
        });
        //This code for email type filter
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
        generateHeadfilterEvents(grid4);
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
                    grid4.getDataTable().ajax.reload(null, false);
                },
                error: function() {
                    console.log('error!');
                }
            });
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
        $('a[data-toggle="tab"][id="MenuItem5"]').on('shown.bs.tab', function(e) {
            $("#hidefilter").hide();
            e.preventDefault();
            grid4.setAjaxParam("id", grid4.getSelectedRows());
            grid4.setAjaxParam("customFilterIdentity", "");
            grid4.getDataTable().ajax.reload();
        });

    }
    return {
        //main function to initiate the module
        init: function() {
            handleRecords4();
        }
    };
}();

var grid5 = '';
var TableDatatablesAjax5 = function() {
    var handleRecords5 = function() {
        grid5 = new Datatable();
        grid5.init({
            src: $("#datatable_ajax5"),
            onSuccess: function(grid5, response) {
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
                $('[data-bs-toggle="tooltip"]').tooltip();
                // grid:        grid object
                // response:    json object of server side ajax response
                // execute some code after table records loaded
            },
            onError: function(grid5) {
                // execute some code on network or other general error  
            },
            onDataLoad: function(grid5) {
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
                    'class': 'text-center',
                    'bSortable': false
                }, {
                    "data": 1,
                    'class': 'text-center mob-show_div',
                    'bSortable': false
                }, {
                    "data": 2,
                    'class': 'text-left Pages_title_R_2 mob-show_div',
                    'name': 'varTitle'
                }, {
                    "data": 3,
                    'class': 'text-center Pages_module_R_3',
                    'bSortable': false
                }, {
                    "data": 4,
                    "name": 'dtDateTime',
                    className: 'text-center Pages_sdate_R_4'
                }, {
                    "data": 5,
                    "name": 'dtEndDateTime',
                    className: 'text-center Pages_edate_R_5'
                }, {
                    "data": 6,
                    'class': 'text-center Pages_hits_R_6',
                    'bSortable': false
                }, {
                    "data": 7,
                    'class': 'text-center Pages_mdate_R_7',
                    'bSortable': true,
                    'name': 'updated_at'
                }, {
                    "data": 8,
                    'class': 'text-right Pages_dactions_R_8 last_td_action mob-show_div',
                    'bSortable': false,
                }, ],
                "ajax": {
                    "url": window.site_url + "/powerpanel/pages/get_list_archive", // ajax source
                },
                "order": [
                        [4, "desc"]
                    ] // set first column as a default sort by asc
            }
        });

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
        //This code for search filter
        $(document).on('keyup', '#searchfilter', function(e) {
            e.preventDefault();
            var action = $('#searchfilter').val();
            if (action.length >= 2) {
                $.cookie('cmsPagesSearch', action);
                grid5.setAjaxParam("customActionType", "group_action");
                grid5.setAjaxParam("searchValue", action);
                grid5.setAjaxParam("id", grid5.getSelectedRows());
                grid5.getDataTable().ajax.reload();
            } else if (action.length < 1) {
                $.removeCookie('cmsPagesSearch');
                grid5.setAjaxParam("customActionType", "group_action");
                grid5.setAjaxParam("searchValue", action);
                grid5.setAjaxParam("id", grid5.getSelectedRows());
                grid5.getDataTable().ajax.reload();
            }
        });
        //This code for email type filter
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
        generateHeadfilterEvents(grid5);
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
                    grid5.getDataTable().ajax.reload(null, false);
                },
                error: function() {
                    console.log('error!');
                }
            });
        });

        if (!showChecker) {
            grid5.getDataTable().column(0).visible(false);
        } else {
            grid5.getDataTable().column(0).visible(true);
        }
        grid5.setAjaxParam("customActionType", "group_action");
        grid5.clearAjaxParams();
        grid5.getDataTable().columns().iterator('column', function(ctx, idx) {
            $(grid5.getDataTable().column(idx).header()).append('<span class="sort-icon"/>');
        });
        $('a[data-toggle="tab"][id="MenuItem6"]').on('shown.bs.tab', function(e) {
            $("#hidefilter").hide();
            e.preventDefault();
            grid5.setAjaxParam("id", grid5.getSelectedRows());
            grid5.setAjaxParam("customFilterIdentity", "");
            grid5.getDataTable().ajax.reload();
        });

    }
    return {
        //main function to initiate the module
        init: function() {
            handleRecords5();
        }
    };
}();

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
$(window).on('load', function() {
    /*if ($.cookie('cmsPagesSearch')) {
     $.removeCookie('cmsPagesSearch');
     $('#searchfilter').val($.cookie('cmsPagesSearch'));
     $('#searchfilter').trigger('keyup');
     }*/
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

    if (!$.fn.DataTable.isDataTable('#datatable_ajax_approved')) {
        TableDatatablesAjax1.init();
        $('.list_head_filter').trigger('change');
    }
    $("#hidefilter").show();
});
$('a[data-toggle="tab"][id="MenuItem3"]').on('shown.bs.tab', function(e) {

    if (!$.fn.DataTable.isDataTable('#datatable_ajax2')) {
        $('.list_head_filter').trigger('change');
        TableDatatablesAjax2.init();
    }
    $("#hidefilter").hide();
});
$('a[data-toggle="tab"][id="MenuItem4"]').on('shown.bs.tab', function(e) {

    if (!$.fn.DataTable.isDataTable('#datatable_ajax3')) {
        $('.list_head_filter').trigger('change');
        TableDatatablesAjax3.init();
    }
    $("#hidefilter").hide();
});
$('a[data-toggle="tab"][id="MenuItem5"]').on('shown.bs.tab', function(e) {

    if (!$.fn.DataTable.isDataTable('#datatable_ajax4')) {
        $('.list_head_filter').trigger('change');
        TableDatatablesAjax4.init();
    }
    $("#hidefilter").hide();
});
$('a[data-toggle="tab"][id="MenuItem6"]').on('shown.bs.tab', function(e) {

    if (!$.fn.DataTable.isDataTable('#datatable_ajax5')) {
        $('.list_head_filter').trigger('change');
        TableDatatablesAjax5.init();
    }
    $("#hidefilter").hide();
});