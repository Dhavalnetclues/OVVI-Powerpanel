var gridRows = 0;
var grid = '';
if(searchid != ''){
        var id = '?id='+searchid;
    }else{
        var id = '';
    }
var TableDatatablesAjax = function () {
    var handleRecords = function () {
        var action = $('#category').val();
        grid = new Datatable();
        grid.setAjaxParam("catValue", action);
        var totalRec;
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
            totalRec = response.recordsTotal;
                // grid:        grid object
                // response:    json object of server side ajax response
                // execute some code after table records loaded
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
                    },  {
                        "data": 1,
                        className: 'text-left mob-show_div',
                        "name": 'varName',
                        "bSortable": true
                    }, {
                        "data": 2,
                        className: 'text-left mob-show_div',
                        "bSortable": true
                    },
                    {
                        "data": 3,
                        className: 'text-center',
                        "bSortable": false
                    },
                    {
                        "data": 4,
                        className: 'text-center',
                        "name": 'varIpAddress'
                    },
                     {
                        "data": 5,
                        className: 'text-center',
                        "name": 'created_at'
                    },
                    ],
                
                "ajax": {
                    "url": window.site_url + "/powerpanel/formbuilder-lead/get_list"+id, // ajax source
                },
                'fnCreatedRow': function (nRow, aData, iDataIndex) {
                    $(nRow).attr('data-order', aData[5]);
                },
                "order": [
                    [5, "desc"]
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
                $.cookie('videoGallerysearch', action);
                grid.setAjaxParam("customActionType", "group_action");
                grid.setAjaxParam("searchValue", action);
                grid.setAjaxParam("id", grid.getSelectedRows());
                grid.getDataTable().ajax.reload();
            } else {
                $.removeCookie('videoGallerysearch');
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
        $(document).on('change', '#category', function (e) {
            e.preventDefault();
            var action = $('#category').val();

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
        $('a[data-toggle="tab"][id="MenuItem1"]').on('shown.bs.tab', function (e) {
            $("#hidefilter").show();
            e.preventDefault();
            grid.setAjaxParam("id", grid.getSelectedRows());
            grid.getDataTable().ajax.reload();
        });

//        if (!showChecker) {
//            grid.getDataTable().column(6).visible(false);
//            grid.getDataTable().column(7).visible(false);
//        } else {
//            if ($.cookie('VideoGallery_displayorder_P_6') == 'Y') {
//                grid.getDataTable().column(6).visible(true);
//            }
//            if ($.cookie('VideoGallery_publish_P_7') == 'Y') {
//                grid.getDataTable().column(7).visible(true);
//            }
//        }

        $(document).on("click", '#grpChkBox .checkbox_P', function () {
            if ($(this).prop("checked") == true) {
                var datatable_P = $(this).attr("name").split("_");
                grid.getDataTable().column(datatable_P[3]).visible(true);
                Cookies.set($(this).attr("name"), 'Y');
                grid.getDataTable().ajax.reload();
            }
            else {
                var datatable_P = $(this).attr("name").split("_");
                grid.getDataTable().column(datatable_P[3]).visible(false);
                Cookies.set($(this).attr("name"), 'N');
                grid.getDataTable().ajax.reload();
            }
        });
         $('#ExportRecord').on('click', function (e) {
            e.preventDefault();
            if (totalRec < 1) {
                $('#noRecords').modal('show');
            } else {
                $('#noRecords').modal('hide');
                var exportRadioVal = $("input[name='export_type']:checked").val();
                if (exportRadioVal != '') {
                    if (exportRadioVal == 'selected_records') {
                        if ($('#ExportRecord').click) {
                            if ($('input[name="delete[]"]:checked').val()) {
                                ip = '?' + $('input[name="delete[]"]:checked').serialize() + '&' + 'export_type' + '=' + exportRadioVal;
                                var ajaxurl = window.site_url + "/powerpanel/formbuilder-lead/ExportRecord" + ip;
                                window.location = ajaxurl;
                                grid.getDataTable().ajax.reload();
                            } else {
                                $('#noSelectedRecords').modal('show');
                            }
                        }
                    } else {
                        $('#selected_records').modal('hide');
                        var ajaxurl = window.site_url + "/powerpanel/formbuilder-lead/ExportRecord";
                        window.location = ajaxurl;
                    }
                }
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

$(window).on('load', function () {
    if ($.cookie('videoGallerysearch')) {
        $('#searchfilter').val($.cookie('videoGallerysearch'));
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

function reorder(curOrder, excOrder) {
    var ajaxurl = site_url + '/powerpanel/formbuilder-lead/reorder';
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