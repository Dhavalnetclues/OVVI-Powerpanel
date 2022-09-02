var gridRows = 0;
var grid;
var TableDatatablesAjax = function () {
    var initPickers = function () {
    }
    var handleRecords = function () {
        var action = $('#category_id').val();
        grid = new Datatable();
        if(!tableState){
              $.removeCookie('projectSearch');
              $.removeCookie('statusValue');
              $.removeCookie('catValue');
          $("#datatable_ajax").DataTable().state.clear();
          $("#datatable_ajax").DataTable().destroy();
        }
        if (tableState) {
              if($.cookie('projectSearch')) {
                var projectSearch = $.cookie('projectSearch');
                $('#searchfilter').val(projectSearch);
                grid.setAjaxParam("searchValue", projectSearch);
                $('#searchfilter').trigger('keyup');
           }  
          if($.cookie('statusValue') && $.cookie('statusValue') != 'null' && $.cookie('statusValue') != ''){
                    $('#statusfilter').val($.cookie('statusValue'));
                    grid.setAjaxParam("statusValue", $.cookie('statusValue'));
                    $('#statusfilter').trigger('change');   
             }
            if($.cookie('catValue') && $.cookie('catValue') != 'null' && $.cookie('catValue') != ''){
                    $('#category_id').val($.cookie('catValue'));
                    grid.setAjaxParam("catValue", $.cookie('catValue'));
                    $('#category_id').trigger('change');    
            }     
          }
        grid.setAjaxParam("catValue", action);
        grid.init({
            src: $("#datatable_ajax"),
            onSuccess: function (grid, response) {
                if (response.recordsTotal < 1) {
                    $('.deleteMass').hide();
                } else {
                    $('.deleteMass').show();
                }
                // grid:        grid object
                // response:    json object of server side ajax response
                // execute some code after table records loaded
            },
            onError: function (grid) {
                // execute some code on network or other general error  
            },
            onDataLoad: function (grid) {
                // execute some code on ajax data load
                // if ($('.pagination-panel .prev').hasClass('disabled')) {
                //     $("#datatable_ajax tbody tr:first").find('.moveUp').hide();
                // }
                // if ($('.pagination-panel .next').hasClass('disabled')) {
                //     $("#datatable_ajax tbody tr:last").find('.moveDwn').hide();
                // }
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
                "columns": [{
                        "data": 0,
                        className: 'reorder',
                        "bSortable": false
                    }, {
                        "data": 1,
                        "name": 'varTitle',
                        className: 'text-left'
                    }, {
                        "data": 2,
                        className: 'text-center',
                        "bSortable": false
                    }, {
                        "data": 3,
                        "bSortable": false,
                        className: 'text-center'
                    }, {
                        "data": 4,
                        "bSortable": false,
                        className: 'text-center'
                    }, {
                        "data": 5,
                        "bSortable": false,
                        className: 'text-center'
                    }, {
                        "data": 6,
                        "name": 'intDisplayOrder',
                        className: 'text-center'
                    },
                    {
                        "data": 7,
                        "bSortable": false,
                        className: 'text-center publish_switch'
                    }, /*{
                        "data": 7,
                        "bSortable": false,
                        className: 'text-center publish_switch',
                    },*/
                    {
                        "data": 8,
                        "bSortable": false,
                        className: 'text-right'
                    },
                    {
                        "data": 9,
                        "bSortable": false
                    }],
                "columnDefs": [{
                        "targets": [9],
                        "visible": false,
                        "searchable": false
                    }],
                "ajax": {
                    "url": window.site_url + "/powerpanel/projects/get_list", // ajax source
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
         $(document).on('change', '#statusfilter', function (e) {
            e.preventDefault();
            var action = $('#statusfilter').val();
            var catValue = $('#category_id').val();
            grid.setAjaxParam("catValue", catValue);
            if (action != "") {
                $.cookie('statusValue',action);
                grid.setAjaxParam("customActionType", "group_action");
                grid.setAjaxParam("statusValue", action);
                grid.setAjaxParam("id", grid.getSelectedRows());
                grid.getDataTable().ajax.reload();
            } else {
                $.removeCookie('statusValue');
                grid.setAjaxParam("customActionType", "group_action");
                grid.setAjaxParam("statusValue", action);
                grid.setAjaxParam("id", grid.getSelectedRows());
            }
        });

        $(document).on('change', '#projectStatusfilter', function (e) {
            e.preventDefault();
            var action = $('#projectStatusfilter').val();
            if (action != "") {
                grid.setAjaxParam("customActionType", "group_action");
                grid.setAjaxParam("projectStatusValue", action);
                grid.setAjaxParam("id", grid.getSelectedRows());
                grid.getDataTable().ajax.reload();
            } else {
                grid.setAjaxParam("customActionType", "group_action");
                grid.setAjaxParam("projectStatusValue", action);
                grid.setAjaxParam("id", grid.getSelectedRows());
            }
        });

            $(document).on('change','#statusfilter',function(e){
                    e.preventDefault();
                   var action = $('#statusfilter').val();
                    var catValue = $('#category_id').val();
                    grid.setAjaxParam("catValue", catValue);
                    if(action != ""){
                        $.cookie('statusValue',action);
                        grid.setAjaxParam("customActionType",'group_action');
                        grid.setAjaxParam('statusValue',action);
                        grid.setAjaxParam('id',grid.getSelectedRows());
                        grid.getDataTable().ajax.reload();                      
                    }else{
                        $.removeCookie('statusValue');
                        grid.setAjaxParam("customActionType",'group_action');
                        grid.setAjaxParam('statusValue',action);
                        grid.setAjaxParam('id',grid.getSelectedRows());
                    }
                });

        $(document).on('change', '#category_id', function (e) {
            e.preventDefault();
            var action = $('#category_id').val();
            var actionsearch = $('#searchfilter').val();
            var actionStatus  = $('#statusfilter').val();
            grid.setAjaxParam("catValue", actionsearch);
            grid.setAjaxParam("statusValue", actionStatus);
            if (action != "") {
                $.cookie('catValue',action);
                grid.setAjaxParam("customActionType", "group_action");
                grid.setAjaxParam("catValue", action);
                grid.setAjaxParam("id", grid.getSelectedRows());
                grid.getDataTable().ajax.reload();
            } else {
                $.removeCookie('catValue');
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
            var catValue = $('#category_id').val();
            var actionStatus  = $('#statusfilter').val();
            grid.setAjaxParam("catValue", catValue);
            grid.setAjaxParam("statusValue", actionStatus);
                        
            if (action.length >= 2) {
                $.cookie('projectSearch',action);
                grid.setAjaxParam("customActionType", "group_action");
                grid.setAjaxParam("searchValue", action);
                grid.setAjaxParam("id", grid.getSelectedRows());
                grid.getDataTable().ajax.reload();
            } else if (action.length < 1) {
                $.removeCookie('projectSearch');
                grid.setAjaxParam("customActionType", "group_action");
                grid.setAjaxParam("searchValue", action);
                grid.setAjaxParam("id", grid.getSelectedRows());
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
        init: function () {
            initPickers();
            handleRecords();
        }
    };
}();
jQuery(document).ready(function () {
    $.ajaxSetup({
        headers: {
            'X-CSRF-Token': $('input[name="_token"]').val()
        }
    });
    TableDatatablesAjax.init();
});

function reorder(curOrder, excOrder) {
    var ajaxurl = site_url + '/powerpanel/projects/reorder';
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

function makeFeatured(fid, status) {
    var ajaxurl = site_url + '/powerpanel/projects/makeFeatured';
    $.ajax({
        url: ajaxurl,
        data: {id: fid, featured: status},
        type: "POST",
        dataType: "json",
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