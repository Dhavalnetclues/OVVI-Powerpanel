var gridRows = 0;
var grid;
var TableDatatablesAjax = function () {
    var initPickers = function () {
    }
    var handleRecords = function () {
        var action = $('#category_id').val();
        grid = new Datatable();
        grid.setAjaxParam("catValue", action);
        grid.init({
            src: $("#datatable_ajax"),
            onSuccess: function (grid, response) {
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
            },
            onError: function (grid) {
                // execute some code on network or other general error  
            },
            onDataLoad: function (grid) {
                // execute some code on ajax data load
                $('.make-switch').bootstrapSwitch();
            },
            loadingMessage: 'Loading...',
            dataTable: {
                // here you can define a typical datatable settings from http://datatables.net/usage/options 
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
                    {
                        "data": 0,
                        className: 'td_checker',
                        'bSortable': false
                    },
                    {
                        "data": 1,
                        className: 'td_checker mob-show_div',
                        'bSortable': false
                    },
                    {
                        "data": 2,
                        "name": 'varTitle',
                        className: 'text-left mob-show_div PhotoAlbum_title_P_2',
                        'bSortable': true
                    },
                    {
                        "data": 3,
                        "name": 'dtDateTime',
                        className: 'text-center PhotoAlbum_sdate_P_3'
                    },
                    {
                        "data": 4,
                        "name": 'dtEndDateTime',
                        className: 'text-center PhotoAlbum_edate_P_4'
                    },
                    {
                        "data": 5,
                        "bSortable": false,
                        className: 'text-center PhotoAlbum_image_P_5'
                    }, {
                        "data": 6,
                        "bSortable": false,
                        className: 'text-center PhotoAlbum_photo_P_6'
                    }, {
                        "data": 7,
                        "bSortable": false,
                        className: 'text-center PhotoAlbum_hits_P_7'
                    }, {
                        "data": 8,
                        className: 'text-center PhotoAlbum_order_P_8',
                        "name": 'intDisplayOrder'
                    }, {
                        "data": 9,
                        "bSortable": false,
                        className: 'text-center publish_switch PhotoAlbum_publish_P_9',
                    }, {
                        "data": 10,
                        "bSortable": false,
                        className: 'text-right last_td_action mob-show_div PhotoAlbum_dactions_P_10'
                    }, {
                        "data": 11,
                        "bSortable": false,
                        className: 'text-right'
                    }],
                "columnDefs": [{
                        "targets": [11],
                        "visible": false,
                        "searchable": false
                    }],
                "ajax": {
                    "url": window.site_url + "/powerpanel/photo-album/get_list", // ajax source
                },
                'fnCreatedRow': function (nRow, aData, iDataIndex) {
                    $(nRow).attr('data-order', aData[10]);
                },
                "order": [
                    [8, "asc"]
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
                $.cookie('photoAlbumsearch', action);
                grid.setAjaxParam("customActionType", "group_action");
                grid.setAjaxParam("searchValue", action);
                grid.setAjaxParam("id", grid.getSelectedRows());
                grid.getDataTable().ajax.reload();
            } else if (action.length < 1) {
                $.removeCookie('photoAlbumsearch');
                grid.setAjaxParam("customActionType", "group_action");
                grid.setAjaxParam("searchValue", action);
                grid.setAjaxParam("id", grid.getSelectedRows());
                grid.getDataTable().ajax.reload();
            }
        });

        $(document).on('click', '#refresh', function (e) {
            $('#start_date').val('');
            $('#end_date').val('');
            grid.setAjaxParam("rangeFilter", '');
            grid.getDataTable().ajax.reload();
        });
        $(document).on('click', '#photoAlbumRange', function (e) {
            e.preventDefault();
            var action = {};
            action['from'] = $('#start_date').val();
            action['to'] = $('#end_date').val();
            if (action['from'] != '' || action['to'] != '') {
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

        if (!showChecker) {
            if (grid != "") {
                grid.getDataTable().column(8).visible(false);
                grid.getDataTable().column(9).visible(false);
            } else {
                grid.getDataTable().column(8).visible(true);
                grid.getDataTable().column(8).visible(true);
            }
        }
        grid.setAjaxParam("customActionType", "group_action");
        grid.clearAjaxParams();
        grid.getDataTable().columns().iterator('column', function (ctx, idx) {
            $(grid.getDataTable().column(idx).header()).append('<span class="sort-icon"/>');
        });
    }
    return {
        init: function () {
            initPickers();
            handleRecords();
        }
    };
}();

var grid1;
var TableDatatablesAjaxApproval = function () {
    var initPickers = function () {
    }
    var handleRecords1 = function () {
        var action = $('#category_id').val();
        grid1 = new Datatable();
        grid1.setAjaxParam("catValue", action);
        grid1.init({
            src: $("#datatable_ajax_approved"),
            onSuccess: function (grid1, response) {
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
            },
            onError: function (grid1) {
                // execute some code on network or other general error  
            },
            onDataLoad: function (grid1) {
                // execute some code on ajax data load
                $('.make-switch').bootstrapSwitch();
            },
            loadingMessage: 'Loading...',
            dataTable: {
                // here you can define a typical datatable settings from http://datatables.net/usage/options 
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
                    {
                        "data": 0,
                        "bSortable": false,
                        className: 'text-center mob-show_div'
                    },
                    {
                        "data": 1,
                        "name": 'varTitle',
                        className: 'text-left mob-show_div PhotoAlbum_title_A_2',
                        'bSortable': true
                    },
                    {
                        "data": 2,
                        "name": 'dtDateTime',
                        className: 'text-center PhotoAlbum_sdate_A_3',
                        'bSortable': true
                    },
                    {
                        "data": 3,
                        "name": 'dtEndDateTime',
                        className: 'text-center PhotoAlbum_edate_A_4'
                    },
                    {
                        "data": 4,
                        "bSortable": false,
                        className: 'text-center PhotoAlbum_image_A_5'
                    }, {
                        "data": 5,
                        "bSortable": false,
                        className: 'text-center PhotoAlbum_photo_A_6'
                    }, {
                        "data": 6,
                        "bSortable": false,
                        className: 'text-center PhotoAlbum_hits_A_7'
                    }, {
                        "data": 7,
                        "bSortable": false,
                        className: 'text-right last_td_action mob-show_div PhotoAlbum_dactions_P_10'
                    }],
                "ajax": {
                    "url": window.site_url + "/powerpanel/photo-album/get_list_New", // ajax source
                },
                "order": [
                    [2, "desc"]
                ]
            }
        });
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
        generateHeadfilterEvents(grid1);
        $(document).on('change', '#category_id', function (e) {
            e.preventDefault();
            var action = $('#category_id').val();

            if (action != "") {
                grid1.setAjaxParam("customActionType", "group_action");
                grid1.setAjaxParam("catValue", action);
                grid1.setAjaxParam("id", grid1.getSelectedRows());
                grid1.getDataTable().ajax.reload();
            } else {
                grid1.setAjaxParam("customActionType", "group_action");
                grid1.setAjaxParam("catValue", action);
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
                $.cookie('photoAlbumsearch', action);
                grid1.setAjaxParam("customActionType", "group_action");
                grid1.setAjaxParam("searchValue", action);
                grid1.setAjaxParam("id", grid1.getSelectedRows());
                grid1.getDataTable().ajax.reload();
            } else if (action.length < 1) {
                $.removeCookie('photoAlbumsearch');
                grid1.setAjaxParam("customActionType", "group_action");
                grid1.setAjaxParam("searchValue", action);
                grid1.setAjaxParam("id", grid1.getSelectedRows());
                grid1.getDataTable().ajax.reload();
            }
        });

        $(document).on('click', '#refresh', function (e) {
            $('#start_date').val('');
            $('#end_date').val('');
            grid1.setAjaxParam("rangeFilter", '');
            grid1.getDataTable().ajax.reload();
        });
        $(document).on('click', '#photoAlbumRange', function (e) {
            e.preventDefault();
            var action = {};
            action['from'] = $('#start_date').val();
            action['to'] = $('#end_date').val();
            if (action['from'] != '' || action['to'] != '') {
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

        $('a[data-toggle="tab"][id="MenuItem2"]').on('shown.bs.tab', function (e) {
            $("#hidefilter").show();
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
        init: function () {
            initPickers();
            handleRecords1();
        }
    };
}();

var gridRows2 = 0;
var grid2;
var TableDatatablesAjax2 = function () {
    var initPickers2 = function () {
    }
    var handleRecords2 = function () {
        var action = $('#category_id').val();
        grid2 = new Datatable();
        grid2.setAjaxParam("catValue", action);
        grid2.init({
            src: $("#datatable_ajax2"),
            onSuccess: function (grid2, response) {
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
                          if (index == 'D') {
                              $.each(value, function (index, columnid) {
                                  $('#datatable_ajax2 thead').find('.'+columnid).addClass("hidecolumn");
                                  $("#datatable_ajax2 tbody").find('tr').each(function (index, value) {
                                      $(this).find('.'+columnid).addClass("hidecolumn");
                                  });
                              });
                          }
                      });   
                }, 1500);

            },
            onError: function (grid2) {
                // execute some code on network or other general error  
            },
            onDataLoad: function (grid2) {
                // execute some code on ajax data load
                $('.make-switch').bootstrapSwitch();
            },
            loadingMessage: 'Loading...',
            dataTable: {
                // here you can define a typical datatable settings from http://datatables.net/usage/options 
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
                    {
                        "data": 0,
                        className: 'text-center td_checker mob-show_div',
                        'bSortable': false
                    },
                    {
                        "data": 1,
                        "name": 'varTitle',
                        className: 'text-left mob-show_div PhotoAlbum_title_D_2',
                        'bSortable': true
                    },
                    {
                        "data": 2,
                        "name": 'dtDateTime',
                        className: 'text-center PhotoAlbum_sdate_D_3'
                    },
                    {
                        "data": 3,
                        "name": 'dtEndDateTime',
                        className: 'text-center PhotoAlbum_edate_D_4'
                    },
                    {
                        "data": 4,
                        "bSortable": false,
                        className: 'text-center PhotoAlbum_image_D_5'
                    }, {
                        "data": 5,
                        "bSortable": false,
                        className: 'text-center PhotoAlbum_photo_D_6'
                    }, {
                        "data": 6,
                        "bSortable": false,
                        className: 'text-center PhotoAlbum_hits_D_7'
                    }, {
                        "data": 7,
                        "bSortable": false,
                        className: 'text-center publish_switch PhotoAlbum_publish_D_9',
                    }, {
                        "data": 8,
                        "bSortable": false,
                        className: 'text-right last_td_action mob-show_div PhotoAlbum_dactions_D_10'
                    }, {
                        "data": 9,
                        "bSortable": false,
                        className: 'text-right'
                    }],
                "columnDefs": [{
                        "targets": [9],
                        "visible": false,
                        "searchable": false
                    }],
                "ajax": {
                    "url": window.site_url + "/powerpanel/photo-album/get_list_draft", // ajax source
                },
                'fnCreatedRow': function (nRow, aData, iDataIndex) {
                    $(nRow).attr('data-order', aData[8]);
                },
                "order": [
                    [2, "desc"]
                ]
            }
        });

        $('#datatable_ajax2 tbody').on('click', '.moveDwn', function () {
            var order = $(this).data('order');
            exOrder = order + 1;
            reorder(order, exOrder);
        });
        $('#datatable_ajax2 tbody').on('click', '.moveUp', function () {
            var order = $(this).data('order');
            exOrder = order - 1;
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
                $.cookie('photoAlbumsearch', action);
                grid2.setAjaxParam("customActionType", "group_action");
                grid2.setAjaxParam("searchValue", action);
                grid2.setAjaxParam("id", grid2.getSelectedRows());
                grid2.getDataTable().ajax.reload();
            } else if (action.length < 1) {
                $.removeCookie('photoAlbumsearch');
                grid2.setAjaxParam("customActionType", "group_action");
                grid2.setAjaxParam("searchValue", action);
                grid2.setAjaxParam("id", grid2.getSelectedRows());
                grid2.getDataTable().ajax.reload();
            }
        });

        $(document).on('click', '#refresh', function (e) {
            $('#start_date').val('');
            $('#end_date').val('');
            grid2.setAjaxParam("rangeFilter", '');
            grid2.getDataTable().ajax.reload();
        });
        $(document).on('click', '#photoAlbumRange', function (e) {
            e.preventDefault();
            var action = {};
            action['from'] = $('#start_date').val();
            action['to'] = $('#end_date').val();
            if (action['from'] != '' || action['to'] != '') {
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

        $('a[data-toggle="tab"][id="MenuItem3"]').on('shown.bs.tab', function (e) {
            $("#hidefilter").hide();
            e.preventDefault();
            grid2.setAjaxParam("id", grid2.getSelectedRows());
            grid2.getDataTable().ajax.reload();
        });
        
        grid2.setAjaxParam("customActionType", "group_action");
        grid2.clearAjaxParams();
        grid2.getDataTable().columns().iterator('column', function (ctx, idx) {
            $(grid2.getDataTable().column(idx).header()).append('<span class="sort-icon"/>');
        });
    }
    return {
        init: function () {
            initPickers2();
            handleRecords2();
        }
    };
}();


var gridRows3 = 0;
var grid3;
var TableDatatablesAjax3 = function () {
    var initPickers3 = function () {
    }
    var handleRecords3 = function () {
        var action = $('#category_id').val();
        grid3 = new Datatable();
        grid3.setAjaxParam("catValue", action);
        grid3.init({
            src: $("#datatable_ajax3"),
            onSuccess: function (grid3, response) {
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
                          if (index == 'T') {
                              $.each(value, function (index, columnid) {
                                   $('#datatable_ajax3 thead').find('.'+columnid).addClass("hidecolumn");
                                  $("#datatable_ajax3 tbody").find('tr').each(function (index, value) {
                                      $(this).find('.'+columnid).addClass("hidecolumn");
                                  });
                              });
                          }
                      });   
                }, 1500);
            },
            onError: function (grid3) {
                // execute some code on network or other general error  
            },
            onDataLoad: function (grid3) {
                // execute some code on ajax data load
                $('.make-switch').bootstrapSwitch();
            },
            loadingMessage: 'Loading...',
            dataTable: {
                // here you can define a typical datatable settings from http://datatables.net/usage/options 
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
                    {
                        "data": 0,
                        className: 'td_checker text-center mob-show_div',
                        'bSortable': false
                    },
                    {
                        "data": 1,
                        "name": 'varTitle',
                        className: 'text-left mob-show_div PhotoAlbum_title_T_2',
                        'bSortable': true
                    },
                    {
                        "data": 2,
                        "name": 'dtDateTime',
                        className: 'text-center PhotoAlbum_sdate_T_3'
                    },
                    {
                        "data": 3,
                        "name": 'dtEndDateTime',
                        className: 'text-center PhotoAlbum_edate_T_4'
                    },
                    {
                        "data": 4,
                        "bSortable": false,
                        className: 'text-center PhotoAlbum_image_T_5'
                    }, {
                        "data": 5,
                        "bSortable": false,
                        className: 'text-center PhotoAlbum_photo_T_6'
                    }, {
                        "data": 6,
                        "bSortable": false,
                        className: 'text-center PhotoAlbum_hits_T_7'
                    }, {
                        "data": 7,
                        "bSortable": false,
                        className: 'text-right last_td_action mob-show_div PhotoAlbum_dactions_T_10'
                    }, {
                        "data": 8,
                        "bSortable": false,
                        className: 'text-right'
                    }],
                "columnDefs": [{
                        "targets": [8],
                        "visible": false,
                        "searchable": false
                    }],
                "ajax": {
                    "url": window.site_url + "/powerpanel/photo-album/get_list_trash", // ajax source
                },
                'fnCreatedRow': function (nRow, aData, iDataIndex) {
                    $(nRow).attr('data-order', aData[7]);
                },
                "order": [
                    [2, "desc"]
                ]
            }
        });

        $('#datatable_ajax3 tbody').on('click', '.moveDwn', function () {
            var order = $(this).data('order');
            exOrder = order + 1;
            reorder(order, exOrder);
        });
        $('#datatable_ajax3 tbody').on('click', '.moveUp', function () {
            var order = $(this).data('order');
            exOrder = order - 1;
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
                $.cookie('photoAlbumsearch', action);
                grid3.setAjaxParam("customActionType", "group_action");
                grid3.setAjaxParam("searchValue", action);
                grid3.setAjaxParam("id", grid3.getSelectedRows());
                grid3.getDataTable().ajax.reload();
            } else if (action.length < 1) {
                $.removeCookie('photoAlbumsearch');
                grid3.setAjaxParam("customActionType", "group_action");
                grid3.setAjaxParam("searchValue", action);
                grid3.setAjaxParam("id", grid3.getSelectedRows());
                grid3.getDataTable().ajax.reload();
            }
        });

        $(document).on('click', '#refresh', function (e) {
            $('#start_date').val('');
            $('#end_date').val('');
            grid3.setAjaxParam("rangeFilter", '');
            grid3.getDataTable().ajax.reload();
        });
        $(document).on('click', '#photoAlbumRange', function (e) {
            e.preventDefault();
            var action = {};
            action['from'] = $('#start_date').val();
            action['to'] = $('#end_date').val();
            if (action['from'] != '' || action['to'] != '') {
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

        $('a[data-toggle="tab"][id="MenuItem4"]').on('shown.bs.tab', function (e) {
            $("#hidefilter").hide();
            e.preventDefault();
            grid3.setAjaxParam("id", grid3.getSelectedRows());
            grid3.getDataTable().ajax.reload();
        });
        
        grid3.setAjaxParam("customActionType", "group_action");
        grid3.clearAjaxParams();
        grid3.getDataTable().columns().iterator('column', function (ctx, idx) {
            $(grid3.getDataTable().column(idx).header()).append('<span class="sort-icon"/>');
        });
    }
    return {
        init: function () {
            initPickers3();
            handleRecords3();
        }
    };
}();

var gridRows4 = 0;
var grid4;
var TableDatatablesAjax4 = function () {
    var initPickers4 = function () {
    }
    var handleRecords4 = function () {
        var action = $('#category_id').val();
        grid4 = new Datatable();
        grid4.setAjaxParam("catValue", action);
        grid4.init({
            src: $("#datatable_ajax4"),
            onSuccess: function (grid4, response) {
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
                          if (index == 'F') {
                              $.each(value, function (index, columnid) {
                               $('#datatable_ajax4 thead').find('.'+columnid).addClass("hidecolumn");
                                  $("#datatable_ajax4 tbody").find('tr').each(function (index, value) {
                                      $(this).find('.'+columnid).addClass("hidecolumn");
                                  });
                              });
                          }
                      });   
                }, 1500);
            },
            onError: function (grid4) {
                // execute some code on network or other general error  
            },
            onDataLoad: function (grid4) {
                // execute some code on ajax data load
                $('.make-switch').bootstrapSwitch();
            },
            loadingMessage: 'Loading...',
            dataTable: {
                // here you can define a typical datatable settings from http://datatables.net/usage/options 
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
                    {
                        "data": 0,
                        className: 'td_checker',
                        'bSortable': false
                    },
                    {
                        "data": 1,
                        className: 'td_checker mob-show_div',
                        'bSortable': false
                    },
                    {
                        "data": 2,
                        "name": 'varTitle',
                        className: 'text-left mob-show_div PhotoAlbum_title_F_2',
                        'bSortable': true
                    },
                    {
                        "data": 3,
                        "name": 'dtDateTime',
                        className: 'text-center PhotoAlbum_sdate_F_3'
                    },
                    {
                        "data": 4,
                        "name": 'dtEndDateTime',
                        className: 'text-center PhotoAlbum_edate_F_4'
                    },
                    {
                        "data": 5,
                        "bSortable": false,
                        className: 'text-center PhotoAlbum_image_F_5'
                    }, {
                        "data": 6,
                        "bSortable": false,
                        className: 'text-center PhotoAlbum_photo_F_6'
                    }, {
                        "data": 7,
                        "bSortable": false,
                        className: 'text-center PhotoAlbum_hits_F_7'
                    }, {
                        "data": 8,
                        "bSortable": false,
                        className: 'text-right last_td_action mob-show_div PhotoAlbum_dactions_F_10'
                    }, {
                        "data": 9,
                        "bSortable": false,
                        className: 'text-right'
                    }],
                "columnDefs": [{
                        "targets": [9],
                        "visible": false,
                        "searchable": false
                    }],
                "ajax": {
                    "url": window.site_url + "/powerpanel/photo-album/get_list_favorite", // ajax source
                },
                'fnCreatedRow': function (nRow, aData, iDataIndex) {
                    $(nRow).attr('data-order', aData[9]);
                },
                "order": [
                    [3, "desc"]
                ]
            }
        });

        $('#datatable_ajax4 tbody').on('click', '.moveDwn', function () {
            var order = $(this).data('order');
            exOrder = order + 1;
            reorder(order, exOrder);
        });
        $('#datatable_ajax4 tbody').on('click', '.moveUp', function () {
            var order = $(this).data('order');
            exOrder = order - 1;
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
                $.cookie('photoAlbumsearch', action);
                grid4.setAjaxParam("customActionType", "group_action");
                grid4.setAjaxParam("searchValue", action);
                grid4.setAjaxParam("id", grid4.getSelectedRows());
                grid4.getDataTable().ajax.reload();
            } else if (action.length < 1) {
                $.removeCookie('photoAlbumsearch');
                grid4.setAjaxParam("customActionType", "group_action");
                grid4.setAjaxParam("searchValue", action);
                grid4.setAjaxParam("id", grid4.getSelectedRows());
                grid4.getDataTable().ajax.reload();
            }
        });

        $(document).on('click', '#refresh', function (e) {
            $('#start_date').val('');
            $('#end_date').val('');
            grid4.setAjaxParam("rangeFilter", '');
            grid4.getDataTable().ajax.reload();
        });
        $(document).on('click', '#photoAlbumRange', function (e) {
            e.preventDefault();
            var action = {};
            action['from'] = $('#start_date').val();
            action['to'] = $('#end_date').val();
            if (action['from'] != '' || action['to'] != '') {
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

        $('a[data-toggle="tab"][id="MenuItem5"]').on('shown.bs.tab', function (e) {
            $("#hidefilter").hide();
            e.preventDefault();
            grid4.setAjaxParam("id", grid4.getSelectedRows());
            grid4.getDataTable().ajax.reload();
        });

        grid4.setAjaxParam("customActionType", "group_action");
        grid4.clearAjaxParams();
        grid4.getDataTable().columns().iterator('column', function (ctx, idx) {
            $(grid4.getDataTable().column(idx).header()).append('<span class="sort-icon"/>');
        });
    }
    return {
        init: function () {
            initPickers4();
            handleRecords4();
        }
    };
}();

$(window).on('load', function () {
    if ($.cookie('photoAlbumsearch')) {
        $('#searchfilter').val($.cookie('photoAlbumsearch'));
        $('#searchfilter').trigger('keyup');
    }
});
jQuery(document).ready(function () {
    $.ajaxSetup({
        headers: {
            'X-CSRF-Token': $('input[name="_token"]').val()
        }
    });
    $("#hidefilter").show();
    TableDatatablesAjax.init();
});

$('a[data-toggle="tab"][id="MenuItem2"]').on('shown.bs.tab', function (e) {
    $("#hidefilter").show();
    if (!$.fn.DataTable.isDataTable('#datatable_ajax_approved')) {
        TableDatatablesAjaxApproval.init();
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
function generateHeadfilterEvents(gridvariable) {
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
        gridvariable.setAjaxParam("customFilterIdentity", "");
    });
    $('.list_head_filter').trigger('change');
}
function reorder(curOrder, excOrder) {
    var ajaxurl = site_url + '/powerpanel/photo-album/reorder';
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
            grid1.getDataTable().ajax.reload(null, false);
        },
        error: function () {
            console.log('error!');
        }
    });
}

/*$('a[data-toggle="tab"][id="MenuItem1"]').on('shown.bs.tab', function(e) {
 e.preventDefault();
 grid.getDataTable().ajax.reload(null, false);		
 });
 
 $('a[data-toggle="tab"][id="MenuItem2"]').on('shown.bs.tab', function(e) {
 e.preventDefault();	
 grid1.getDataTable().ajax.reload(null, false);
 });*/

function makeFeatured(fid, status) {
    var ajaxurl = site_url + '/powerpanel/photo-album/makeFeatured';
    $.ajax({
        url: ajaxurl,
        data: {id: fid, featured: status},
        type: "POST",
        dataType: "json",
        success: function (data) {
        },
        complete: function () {
            grid.getDataTable().ajax.reload(null, false);
            grid1.getDataTable().ajax.reload(null, false);
        },
        error: function () {
            console.log('error!');
        }
    });
}
$(document).ready(function () {
			var today = moment.tz("{{Config::get('Constant.DEFAULT_TIME_ZONE')}}").format(DEFAULT_DT_FORMAT);
			$('#start_date').datepicker({
					autoclose: true,
					//startDate: today,
					minuteStep: 5,
					format: DEFAULT_DT_FMT_FOR_DATEPICKER
			}).on("changeDate", function (e) {
					$("#start_date").closest('.has-error').removeClass('has-error');
					$("#app_post_date-error").remove();
					$('#end_date').val('');
					var endingdate = $(this).val();
					var date = new Date(endingdate);
					var enddate = new Date(date.getFullYear(), date.getMonth(), date.getDate() + 1);
					$('#end_date').datepicker('remove');
					$('#end_date').datepicker({
							autoclose: true,
							startDate: enddate,
							minuteStep: 5,
							format: DEFAULT_DT_FMT_FOR_DATEPICKER
					});
			});
			var endingdate = $('#start_date').val();
			var date = new Date(endingdate);
			var enddate = new Date(date.getFullYear(), date.getMonth(), date.getDate() + 1);
			$('#end_date').datepicker({
					autoclose: true,
					startDate: enddate,
					minuteStep: 5,
					format: DEFAULT_DT_FMT_FOR_DATEPICKER
			});
	});