var gridRows = 0;
var grid = '';
var grid1 = '';
var TableDatatablesAjax = function () {

    var initPickers = function () {
        //init date pickers
        $('.date-picker').datepicker({
            rtl: App.isRTL(),
            autoclose: true
        });
    }

    var handleRecords = function () {
        grid = new Datatable();
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
                $('.make-switch').bootstrapSwitch();
            },
            loadingMessage: 'Loading...',
            dataTable: {// here you can define a typical datatable settings from http://datatables.net/usage/options 
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
                "serverSide": true,
                "columns": [{
                        "data": 0,
                        className: 'text-center',
                        "bSortable": false
                    }, {
                        "data": 1,
                        className: 'text-left mob-show_div',
                        "name": 'name'
                    }, {
                        "data": 2,
                        className: 'text-left mob-show_div',
                        "name": 'email'
                    }, {
                        "data": 3,
                        className: 'text-left',
                        "bSortable": false
                    }, {
                        "data": 4,
                        className: 'text-left',
                        "bSortable": false
                    }, {
                        "data": 5,
                        className: 'text-center publish_switch',
                        "bSortable": false
                    }, {
                        "data": 6,
                        className: 'text-center publish_switch mob-show_div',
                        "bSortable": false
                    }, {
                        "data": 7,
                        className: 'text-right last_td_action mob-show_div',
                        "bSortable": false
                    }

                ],
                "ajax": {
                    "url": window.site_url + "/powerpanel/users/get_list", // ajax source
                },
                "order": [
                    [1, "asc"]
                ] // set first column as a default sort by asc
            }
        });
        $(document).on("switchChange.bootstrapSwitch", ".publish", function (event, state) {
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
                    grid.getDataTable().ajax.reload();
                },
                error: function () {
                    console.log('error!');
                }
            });
        });

        $(document).on("click", ".lock-unlock", function (event) {
            var id = $(this).data('id');
            var action = $(this).data('action');
            var url = site_url + '/powerpanel/users/user-locking';
            $.ajax({
                url: url,
                data: {
                    id: id,
                    action: action
                },
                type: "POST",
                dataType: "HTML",
                success: function (data) {
                    grid.getDataTable().ajax.reload();
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
                grid.setAjaxParam("customActionType", "group_action");
                grid.setAjaxParam("searchValue", action);
                grid.setAjaxParam("id", grid.getSelectedRows());
                grid.getDataTable().ajax.reload();
            } else if (action.length < 1) {
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
        
         $(document).on('change', '#statusfilter', function (e) {
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

$(document).on("click", ".reset-link", function (event, state) {
    $('body').loader(loaderConfig);
    var resetEmail = $(this).data('email');
    var url = site_url + '/powerpanel/password/sendResetLinkAjax';
    $.ajax({
        url: url,
        data: {
            email: resetEmail
        },
        type: "POST",
        dataType: "json",
        success: function (data) {
            if (data.error == "false") {
                toastr.success('Password Reset link has been sent to: ' + resetEmail, {timeOut: 5000});
            } else {
                toastr.error('Something went wrong !', {timeOut: 5000});
            }
        },
        error: function () {
            console.log('error!');
        },
        complete: function () {
            $.loader.close(true);
        }
    });
});