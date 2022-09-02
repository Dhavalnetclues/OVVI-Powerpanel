var gridRows = 0;
var grid = '';
var TableDatatablesAjax = function () {
    var handleRecords = function () {
        var action = $('#category').val();
        grid = new Datatable();
        grid.setAjaxParam("catValue", action);
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
            },
            onError: function (grid) {
                // execute some code on network or other general error
            },
            onDataLoad: function (grid) {
                // execute some code on ajax data load
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
                        className: 'text-left mob-show_div',
                        "bSortable": false
                    }, {
                        "data": 2,
                        className: 'text-left mob-show_div PhotoGallery_title_P_2',
                        "name": 'varTitle',
                        "bSortable": true
                    }, {
                        "data": 3,
                        className: 'text-center PhotoGallery_image_P_3',
                        "name": 'fkIntImgId',
                        "bSortable": false
                    }, {
                        "data": 4,
                        className: 'text-center PhotoGallery_cat_P_4',
                        "name": 'intPhotoAlbumId',
                        "bSortable": true
                    }, {
                        "data": 5,
                        className: 'text-center PhotoGallery_sdate_P_5',
                        "name": 'dtDateTime',
                        "bSortable": true
                    }, {
                        "data": 6,
                        className: 'text-center PhotoGallery_edate_P_6',
                        "name": 'dtEndDateTime',
                        "bSortable": true
                    }, {
                        "data": 7,
                        className: 'text-center PhotoGallery_order_P_7',
                        "name": 'intDisplayOrder'
                    }, {
                        "data": 8,
                        className: 'text-center publish_switch PhotoGallery_publish_P_8',
                        "bSortable": false
                    }, {
                        "data": 9,
                        className: 'text-right last_td_action mob-show_div PhotoGallery_dactions_P_9',
                        "bSortable": false
                    }, {
                        "data": 10,
                        className: 'text-center',
                        "bSortable": false
                    }],
                "columnDefs": [{
                        "targets": [10],
                        "visible": false,
                        "searchable": false
                    }],
                "ajax": {
                    "url": window.site_url + "/powerpanel/photo-gallery/get_list", // ajax source
                },
                'fnCreatedRow': function (nRow, aData, iDataIndex) {
                    $(nRow).attr('data-order', aData[9]);
                },
                "order": [
                    [7, "asc"]
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
                $.cookie('photoGallerysearch', action);
                grid.setAjaxParam("customActionType", "group_action");
                grid.setAjaxParam("searchValue", action);
                grid.setAjaxParam("id", grid.getSelectedRows());
                grid.getDataTable().ajax.reload();
            } else {
                $.removeCookie('photoGallerysearch');
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
        $(document).on('change', '#photoalbumfilter', function (e) {
            e.preventDefault();
            var action = $('#photoalbumfilter').val();

            if (action != "") {
                grid.setAjaxParam("customActionType", "group_action");
                grid.setAjaxParam("albumValue", action);
                grid.setAjaxParam("id", grid.getSelectedRows());
                grid.getDataTable().ajax.reload();
            } else {
                grid.setAjaxParam("customActionType", "group_action");
                grid.setAjaxParam("albumValue", action);
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

        $('a[data-toggle="tab"][id="MenuItem1"]').on('shown.bs.tab', function (e) {
            $("#hidefilter").show();
            e.preventDefault();
            grid.setAjaxParam("id", grid.getSelectedRows());
            grid.getDataTable().ajax.reload();
        });

        if (!showChecker) {
            grid.getDataTable().column(7).visible(false);
            grid.getDataTable().column(8).visible(false);
        } else {
            grid.getDataTable().column(7).visible(true);
            grid.getDataTable().column(8).visible(true);
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
            },
            onError: function (grid1) {
                // execute some code on network or other general error
            },
            onDataLoad: function (grid1) {
                // execute some code on ajax data load
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
                        'class': 'text-center mob-show_div',
                        'bSortable': false
                    }, {
                        "data": 1,
                        "bSortable": true,
                        'class': 'text-left mob-show_div PhotoGallery_title_A_2',
                        "name": 'varTitle',
                    }, {
                        "data": 2,
                        className: 'text-center PhotoGallery_image_A_3',
                        "name": 'fkIntImgId'
                    }, {
                        "data": 3,
                        className: 'text-center PhotoGallery_cat_A_4',
                        "name": 'intPhotoAlbumId'
                    }, {
                        "data": 4,
                        className: 'text-center PhotoGallery_sdate_A_5',
                        "name": 'dtDateTime',
                        "bSortable": false
                    }, {
                        "data": 5,
                        className: 'text-center PhotoGallery_edate_A_6',
                        "name": 'dtEndDateTime',
                        "bSortable": false
                    },
                    {
                        "data": 6,
                        className: 'text-right last_td_action mob-show_div PhotoGallery_dactions_A_9',
                        "bSortable": false
                    }, {
                        "data": 7,
                        className: 'text-center',
                        "bSortable": false
                    }],
                "columnDefs": [{
                        "targets": [7],
                        "visible": false,
                        "searchable": false
                    }],
                "ajax": {
                    "url": window.site_url + "/powerpanel/photo-gallery/get_list_New", // ajax source
                },
                'fnCreatedRow': function (nRow, aData, iDataIndex) {
                    $(nRow).attr('data-order', aData[7]);
                },
                "order": [
                    [4, "desc"]
                ]
            }
        });
        $('#datatable_ajax_approved tbody').on('click', '.moveDwn', function () {
            var order = $(this).data('order');
            exOrder = order - 1;
            reorder(order, exOrder);
        });
        $('#datatable_ajax_approved tbody').on('click', '.moveUp', function () {
            var order = $(this).data('order');
            exOrder = order + 1;
            reorder(order, exOrder);
        });
        $(document).on('keyup', '#searchfilter', function (e) {
            e.preventDefault();
            var action = $('#searchfilter').val();
            if (action.length >= 2) {
                $.cookie('photoGallerysearch', action);
                grid1.setAjaxParam("customActionType", "group_action");
                grid1.setAjaxParam("searchValue", action);
                grid1.setAjaxParam("id", grid1.getSelectedRows());
                grid1.getDataTable().ajax.reload();
            } else {
                $.removeCookie('photoGallerysearch');
                grid1.setAjaxParam("customActionType", "group_action");
                grid1.setAjaxParam("searchValue", action);
                grid1.setAjaxParam("id", grid1.getSelectedRows());
                grid1.getDataTable().ajax.reload();
            }
        });

        $(document).on('change', '#photoalbumfilter', function (e) {
            e.preventDefault();
            var action = $('#photoalbumfilter').val();

            if (action != "") {
                grid1.setAjaxParam("customActionType", "group_action");
                grid1.setAjaxParam("albumValue", action);
                grid1.setAjaxParam("id", grid1.getSelectedRows());
                grid1.getDataTable().ajax.reload();
            } else {
                grid1.setAjaxParam("customActionType", "group_action");
                grid1.setAjaxParam("albumValue", action);
                grid1.setAjaxParam("id", grid1.getSelectedRows());
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
        var action = $('#category').val();
        grid2 = new Datatable();
        grid2.setAjaxParam("catValue", action);
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
                        className: 'text-center mob-show_div',
                        "bSortable": false
                    },  {
                        "data": 1,
                        className: 'text-left mob-show_div PhotoGallery_title_D_2',
                        "name": 'varTitle',
                        "bSortable": true
                    }, {
                        "data": 2,
                        className: 'text-center PhotoGallery_image_D_3',
                        "name": 'fkIntImgId',
                        "bSortable": false
                    }, {
                        "data": 3,
                        className: 'text-center PhotoGallery_cat_D_4',
                        "name": 'intPhotoAlbumId',
                        "bSortable": true
                    }, {
                        "data": 4,
                        className: 'text-center PhotoGallery_sdate_D_5',
                        "name": 'dtDateTime',
                        "bSortable": true
                    }, {
                        "data": 5,
                        className: 'text-center PhotoGallery_edate_D_6',
                        "name": 'dtEndDateTime',
                        "bSortable": true
                    }, {
                        "data": 6,
                        className: 'text-center publish_switch PhotoGallery_publish_D_8',
                        "bSortable": false
                    }, {
                        "data": 7,
                        className: 'text-right last_td_action mob-show_div PhotoGallery_dactions_D_9',
                        "bSortable": false
                    }, {
                        "data": 8,
                        className: 'text-center',
                        "bSortable": false
                    }],
                "columnDefs": [{
                        "targets": [8],
                        "visible": false,
                        "searchable": false
                    }],
                "ajax": {
                    "url": window.site_url + "/powerpanel/photo-gallery/get_list_draft", // ajax source
                },
                'fnCreatedRow': function (nRow, aData, iDataIndex) {
                    $(nRow).attr('data-order', aData[7]);
                },
                "order": [
                    [4, "desc"]
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
        $(document).on('keyup', '#searchfilter', function (e) {
            e.preventDefault();
            var action = $('#searchfilter').val();
            if (action.length >= 2) {
                $.cookie('photoGallerysearch', action);
                grid2.setAjaxParam("customActionType", "group_action");
                grid2.setAjaxParam("searchValue", action);
                grid2.setAjaxParam("id", grid2.getSelectedRows());
                grid2.getDataTable().ajax.reload();
            } else {
                $.removeCookie('photoGallerysearch');
                grid2.setAjaxParam("customActionType", "group_action");
                grid2.setAjaxParam("searchValue", action);
                grid2.setAjaxParam("id", grid2.getSelectedRows());
                grid2.getDataTable().ajax.reload();
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
        $(document).on('change', '#photoalbumfilter', function (e) {
            e.preventDefault();
            var action = $('#photoalbumfilter').val();

            if (action != "") {
                grid2.setAjaxParam("customActionType", "group_action");
                grid2.setAjaxParam("albumValue", action);
                grid2.setAjaxParam("id", grid2.getSelectedRows());
                grid2.getDataTable().ajax.reload();
            } else {
                grid2.setAjaxParam("customActionType", "group_action");
                grid2.setAjaxParam("albumValue", action);
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
        var action = $('#category').val();
        grid3 = new Datatable();
        grid3.setAjaxParam("catValue", action);
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
                         className: 'text-center mob-show_div',
                        "bSortable": false
                    }, {
                        "data": 1,
                        className: 'text-left mob-show_div PhotoGallery_title_T_2',
                        "name": 'varTitle',
                        "bSortable": true
                    }, {
                        "data": 2,
                        className: 'text-center PhotoGallery_image_T_3',
                        "name": 'fkIntImgId',
                        "bSortable": false
                    }, {
                        "data": 3,
                        className: 'text-center PhotoGallery_cat_T_4',
                        "name": 'intPhotoAlbumId',
                        "bSortable": true
                    }, {
                        "data": 4,
                        className: 'text-center PhotoGallery_sdate_T_5',
                        "name": 'dtDateTime',
                        "bSortable": true
                    }, {
                        "data": 5,
                        className: 'text-center PhotoGallery_edate_T_6',
                        "name": 'dtEndDateTime',
                        "bSortable": true
                    }, {
                        "data": 6,
                        className: 'text-right last_td_action mob-show_div PhotoGallery_dactions_T_9',
                        "bSortable": false
                    }, {
                        "data": 7,
                        className: 'text-center',
                        "bSortable": false
                    }],
                "columnDefs": [{
                        "targets": [7],
                        "visible": false,
                        "searchable": false
                    }],
                "ajax": {
                    "url": window.site_url + "/powerpanel/photo-gallery/get_list_trash", // ajax source
                },
                'fnCreatedRow': function (nRow, aData, iDataIndex) {
                    $(nRow).attr('data-order', aData[7]);
                },
                "order": [
                    [4, "desc"]
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
        $(document).on('keyup', '#searchfilter', function (e) {
            e.preventDefault();
            var action = $('#searchfilter').val();
            if (action.length >= 2) {
                $.cookie('photoGallerysearch', action);
                grid3.setAjaxParam("customActionType", "group_action");
                grid3.setAjaxParam("searchValue", action);
                grid3.setAjaxParam("id", grid3.getSelectedRows());
                grid3.getDataTable().ajax.reload();
            } else {
                $.removeCookie('photoGallerysearch');
                grid3.setAjaxParam("customActionType", "group_action");
                grid3.setAjaxParam("searchValue", action);
                grid3.setAjaxParam("id", grid3.getSelectedRows());
                grid3.getDataTable().ajax.reload();
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
        $(document).on('change', '#photoalbumfilter', function (e) {
            e.preventDefault();
            var action = $('#photoalbumfilter').val();

            if (action != "") {
                grid3.setAjaxParam("customActionType", "group_action");
                grid3.setAjaxParam("albumValue", action);
                grid3.setAjaxParam("id", grid3.getSelectedRows());
                grid3.getDataTable().ajax.reload();
            } else {
                grid3.setAjaxParam("customActionType", "group_action");
                grid3.setAjaxParam("albumValue", action);
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
        var action = $('#category').val();
        grid4 = new Datatable();
        grid4.setAjaxParam("catValue", action);
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
                         className: 'text-center mob-show_div',
                        "bSortable": false
                    }, {
                        "data": 1,
                        className: 'text-center mob-show_div',
                        "bSortable": false
                    }, {
                        "data": 2,
                        className: 'text-left mob-show_div PhotoGallery_title_F_2',
                        "name": 'varTitle',
                        "bSortable": true
                    }, {
                        "data": 3,
                        className: 'text-center PhotoGallery_image_F_3',
                        "name": 'fkIntImgId',
                        "bSortable": false
                    }, {
                        "data": 4,
                        className: 'text-center PhotoGallery_cat_F_4',
                        "name": 'intPhotoAlbumId',
                        "bSortable": true
                    }, {
                        "data": 5,
                        className: 'text-center PhotoGallery_sdate_F_5',
                        "name": 'dtDateTime',
                        "bSortable": true
                    }, {
                        "data": 6,
                        className: 'text-center PhotoGallery_edate_F_6',
                        "name": 'dtEndDateTime',
                        "bSortable": true
                    }, {
                        "data": 7,
                        className: 'text-right last_td_action mob-show_div PhotoGallery_dactions_F_9',
                        "bSortable": false
                    }, {
                        "data": 8,
                        className: 'text-center',
                        "bSortable": false
                    }],
                "columnDefs": [{
                        "targets": [8],
                        "visible": false,
                        "searchable": false
                    }],
                "ajax": {
                    "url": window.site_url + "/powerpanel/photo-gallery/get_list_favorite", // ajax source
                },
                'fnCreatedRow': function (nRow, aData, iDataIndex) {
                    $(nRow).attr('data-order', aData[8]);
                },
                "order": [
                    [5, "desc"]
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
        $(document).on('keyup', '#searchfilter', function (e) {
            e.preventDefault();
            var action = $('#searchfilter').val();
            if (action.length >= 2) {
                $.cookie('photoGallerysearch', action);
                grid4.setAjaxParam("customActionType", "group_action");
                grid4.setAjaxParam("searchValue", action);
                grid4.setAjaxParam("id", grid4.getSelectedRows());
                grid4.getDataTable().ajax.reload();
            } else {
                $.removeCookie('photoGallerysearch');
                grid4.setAjaxParam("customActionType", "group_action");
                grid4.setAjaxParam("searchValue", action);
                grid4.setAjaxParam("id", grid4.getSelectedRows());
                grid4.getDataTable().ajax.reload();
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
        $(document).on('change', '#photoalbumfilter', function (e) {
            e.preventDefault();
            var action = $('#photoalbumfilter').val();

            if (action != "") {
                grid4.setAjaxParam("customActionType", "group_action");
                grid4.setAjaxParam("albumValue", action);
                grid4.setAjaxParam("id", grid4.getSelectedRows());
                grid4.getDataTable().ajax.reload();
            } else {
                grid4.setAjaxParam("customActionType", "group_action");
                grid4.setAjaxParam("albumValue", action);
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
        //main function to initiate the module
        init: function () {
            handleRecords4();
        }
    };
}();


$(window).on('load', function () {
    if ($.cookie('photoGallerysearch')) {
        $('#searchfilter').val($.cookie('photoGallerysearch'));
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
    if (!$.fn.DataTable.isDataTable('#datatable_ajax_approved')) {
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

function reorder(curOrder, excOrder) {
    var ajaxurl = site_url + '/powerpanel/photo-gallery/reorder';
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