var gridRows = 0;   //  All
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
            onError: function(grid) {
                // execute some code on network or other general error
            },
            onDataLoad: function(grid) {
                // $('.make-switch').bootstrapSwitch();
                $(document).ready(function () {
                    var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
                    var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
                        return new bootstrap.Tooltip(tooltipTriggerEl)
                    })
                });
            },
            loadingMessage: 'Loading...',
            dataTable: {
                "dom": "t <'gridjs-footer' <'gridjs-pagination'i <'gridjs-pages'p>>>",
                "deferRender": true,
                 drawCallback:function(){
                    var $api = this.api();
                    var pages = $api.page.info().pages;
                    var rows = $api.data().length;
                    if(pages<=1){
                        $('.dataTables_info').css('display','none');
                        $('.dataTables_paginate').css('display','none');
                    }
                },
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
                    'name': 'varTitle',
                    'bSortable': true
                }, {
                    "data": 2,
                    'class': 'text-left Pages_sdate_P_2',
                    "name": 'image',
                    'bSortable': false
                }, {
                    "data": 3,
                    'class': 'text-left Pages_sdate_P_2',
                    "name": 'bannertype',
                    'bSortable': false
                }, {
                    "data": 4,
                    'class': 'text-left Pages_sdate_P_2',
                    "name": 'page',
                    'bSortable': false
                }, {
                    "data": 5,
                    'class': 'text-left Pages_sdate_P_2',
                    "name": 'dtDateTime',
                    // 'bSortable': false
                }, {
                    "data": 6,
                    'class': 'text-left Pages_sdate_P_2',
                    "name": 'intDisplayOrder',
                    // 'bSortable': false
                }, {
                    "data": 7,
                    'class': 'text-center form-switch Pages_publish_P_3',
                    'name': 'publish',
                    'bSortable': false
                }, {
                    "data": 8,
                    'class': 'text-center Pages_dactions_P_4 last_td_action mob-show_div',
                    'bSortable': false
                } ],
                "ajax": {
                    "url": window.site_url + "/powerpanel/banners/get_list", // ajax source
                },
                'fnCreatedRow': function(nRow, aData, iDataIndex) {
                    // $(nRow).attr('data-order', aData[9]);
                },
                "order": [
                    [5, "asc"]
                ]
            }
        });

        $('#datatable_ajax tbody').on('click', '.moveDwn', function() {
            var order = $(this).data('order');
            //var exOrder = $('#datatable_ajax tbody').find('tr[data-order=' + order + ']').next().data('order');
            exOrder = order + 1;
            reorder(order, exOrder);
        });
        $('#datatable_ajax tbody').on('click', '.moveUp', function() {
            var order = $(this).data('order');
            //var exOrder = $('#datatable_ajax tbody').find('tr[data-order=' + order + ']').prev().data('order');
            exOrder = order - 1;
            reorder(order, exOrder);
        });

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

        $(document).on("change", ".publish", function(event, state) {
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

        // grid.getDataTable().column(8).visible(true);
        // grid.getDataTable().column(9).visible(true);
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


var grid1Rows = 0;  //  Approval
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
                    $("#menu2.tab-pane .notabreocrd").show();
										$("#menu2.tab-pane .withrecords").hide();
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
                $(document).ready(function () {
                    var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
                    var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
                        return new bootstrap.Tooltip(tooltipTriggerEl)
                    })
                });
                // $('.make-switch').bootstrapSwitch();
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
                    'name': 'varTitle',
                    'bSortable': false
                }, {
                    "data": 2,
                    'class': 'text-left Pages_sdate_P_2',
                    "name": 'image',
                    'bSortable': false
                }, {
                    "data": 3,
                    'class': 'text-left Pages_sdate_P_2',
                    "name": 'bannertype',
                    'bSortable': false
                }, {
                    "data": 4,
                    'class': 'text-left Pages_sdate_P_2',
                    "name": 'page',
                    'bSortable': false
                }, {
                    "data": 5,
                    'class': 'text-left Pages_sdate_P_2',
                    "name": 'dtDateTime',
                }, {
                    "data": 6,
                    'class': 'text-center form-switch Pages_publish_P_3',
                    'name': 'publish',
                    'bSortable': false
                }, {
                    "data": 7,
                    'class': 'text-center Pages_dactions_P_4 last_td_action mob-show_div',
                    'bSortable': false
                } ],
                "ajax": {
                    "url": window.site_url + "/powerpanel/banners/get_list_New", // ajax source
                },
                'fnCreatedRow': function(nRow, aData, iDataIndex) {
                    // $(nRow).attr('data-order', aData[5]);
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

        $(document).on("change", ".publish", function(event, state) {
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
            $.fn.DataTable.ext.pager.numbers_length = 4;
            handleRecords1();
        }
    };
}();


var grid4Rows = 0;  //  Favorite
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
            onError: function(grid4) {
                // execute some code on network or other general error  
            },
            onDataLoad: function(grid4) {
                // $('.make-switch').bootstrapSwitch();
                $(document).ready(function () {
                    var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
                    var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
                        return new bootstrap.Tooltip(tooltipTriggerEl)
                    })
                });
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
                    'name': 'varTitle',
                    'bSortable': false
                }, {
                    "data": 2,
                    'class': 'text-left Pages_sdate_P_2',
                    "name": 'image',
                    'bSortable': false
                }, {
                    "data": 3,
                    'class': 'text-left Pages_sdate_P_2',
                    "name": 'bannertype',
                    'bSortable': false
                }, {
                    "data": 4,
                    'class': 'text-left Pages_sdate_P_2',
                    "name": 'page',
                    'bSortable': false
                }, {
                    "data": 5,
                    'class': 'text-left Pages_sdate_P_2',
                    "name": 'dtDateTime'
                }, {
                    "data": 6,
                    'class': 'text-left Pages_sdate_P_2',
                    "name": 'intDisplayOrder',
                    'bSortable': false
                }, {
                    "data": 7,
                    'class': 'text-center form-switch Pages_publish_P_3',
                    'name': 'publish',
                    'bSortable': false
                }, {
                    "data": 8,
                    'class': 'text-center Pages_dactions_P_4 last_td_action mob-show_div',
                    'bSortable': false
                } ],
                "ajax": {
                    "url": window.site_url + "/powerpanel/banners/get_list_favorite", // ajax source
                },
                'fnCreatedRow': function(nRow, aData, iDataIndex) {
                    // $(nRow).attr('data-order', aData[7]);
                },
                "order": [
                    [5, "desc"]
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

        $(document).on("change", ".publish", function(event, state) {
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

        // if (!showChecker) {
        //     grid4.getDataTable().column(0).visible(false);
        // } else {
        //     grid4.getDataTable().column(0).visible(true);
        // }

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


var grid2Rows = 0;  // Draft
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
            onError: function(grid2) {
                // execute some code on network or other general error  
            },
            onDataLoad: function(grid2) {
                // $('.make-switch').bootstrapSwitch();
                $(document).ready(function () {
                    var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
                    var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
                        return new bootstrap.Tooltip(tooltipTriggerEl)
                    })
                });
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
                    'name': 'varTitle',
                    'bSortable': false
                }, {
                    "data": 2,
                    'class': 'text-left Pages_sdate_P_2',
                    "name": 'image',
                    'bSortable': false
                }, {
                    "data": 3,
                    'class': 'text-left Pages_sdate_P_2',
                    "name": 'bannertype',
                    'bSortable': false
                }, {
                    "data": 4,
                    'class': 'text-left Pages_sdate_P_2',
                    "name": 'page',
                    'bSortable': false
                }, {
                    "data": 5,
                    'class': 'text-left Pages_sdate_P_2',
                    "name": 'dtDateTime',
                }, {
                    "data": 6,
                    'class': 'text-center form-switch Pages_publish_P_3',
                    'name': 'publish',
                    'bSortable': false
                }, {
                    "data": 7,
                    'class': 'text-center Pages_dactions_P_4 last_td_action mob-show_div',
                    'bSortable': false
                } ],
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
        $(document).on("change", ".publish", function(event, state) {
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

        // if (!showChecker) {
        //     grid2.getDataTable().column(0).visible(false);
        // } else {
        //     grid2.getDataTable().column(0).visible(true);
        // }

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


var grid3Rows = 0;  //  Trash
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
            onError: function(grid3) {
                // execute some code on network or other general error  
            },
            onDataLoad: function(grid2) {
                // $('.make-switch').bootstrapSwitch();
                $(document).ready(function () {
                    var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
                    var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
                        return new bootstrap.Tooltip(tooltipTriggerEl)
                    })
                });
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
                    'name': 'varTitle',
                    'bSortable': false
                }, {
                    "data": 2,
                    'class': 'text-left Pages_sdate_P_2',
                    "name": 'image',
                    'bSortable': false
                }, {
                    "data": 3,
                    'class': 'text-left Pages_sdate_P_2',
                    "name": 'bannertype',
                    'bSortable': false
                }, {
                    "data": 4,
                    'class': 'text-left Pages_sdate_P_2',
                    "name": 'page',
                    'bSortable': false
                }, {
                    "data": 5,
                    'class': 'text-left Pages_sdate_P_2',
                    "name": 'dtDateTime',
                    'bSortable': false
                }, {
                    "data": 6,
                    'class': 'text-center Pages_dactions_P_4 last_td_action mob-show_div',
                    'bSortable': false
                } ],
                "ajax": {
                    "url": window.site_url + "/powerpanel/banners/get_list_trash", // ajax source
                },
                'fnCreatedRow': function(nRow, aData, iDataIndex) {
                    // $(nRow).attr('data-order', aData[6]);
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
        $(document).on("change", ".publish", function(event, state) {
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
            $.fn.DataTable.ext.pager.numbers_length = 4;
            handleRecords3();
        }
    };
}();


$(document).ready(function() {
    let cookie = clearcookie;

    if (cookie == 'true') {
        $.removeCookie('bannerSearch');
        $('#searchfilter').val('');
    }
});

$(document).ready(function() {
    let cookie = clearcookie;
    if (cookie == 'true') {
        $.removeCookie('bannerSearch');
        $('#searchfilter').val('');
    }
});
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