var grid = '';
var TableDatatablesAjax = function() {
    var initPickers = function() {
        //init date pickers
        $('.date-picker').datepicker({
            rtl: App.isRTL(),
            autoclose: true
        });
    }
    var handleRecords = function() {
        grid = new Datatable();
        var ip = '';
        var totalRec;
        grid.init({
            src: $("#datatable_ajax"),
            onSuccess: function(grid, response) {
                if (response.recordsTotal < 1) {
                    $('.deleteMass').hide();
                } else {
                    $('.deleteMass').show();
                }
                if (response.recordsTotal < 1) {
                    $('.ExportRecord').hide();
                } else {
                    $('.ExportRecord').show();
                }

                if (response.recordsTotal < 1) {
                    location.reload(true);
                }

                // grid:        grid object
                // response:    json object of server side ajax response
                // execute some code after table records loaded		
                // get all typeable inputs		
                totalRec = response.recordsTotal;
            },
            onError: function(grid) {
                // execute some code on network or other general error
            },
            onDataLoad: function(grid) {
                // execute some code on ajax data load
            },
            loadingMessage: 'Loading...',
            dataTable: { // here you can define a typical datatable settings from http://datatables.net/usage/options
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
                    className: 'td_checker mob-show_div',
                    "bSortable": false
                }, {
                    "data": 1,
                    className: 'text-left mob-show_div',
                    "name": 'varCountry_flag'
                }, {
                    "data": 2,
                    className: 'text-left mob-show_div',
                    "name": 'varCountry_name'
                }, {
                    "data": 3,
                    className: 'text-center mob-show_div',
                    "name": 'varIpAddress'
                }, {
                    "data": 4,
                    className: 'text-center',
                    "bSortable": false
                }, {
                    "data": 5,
                    className: 'text-center',
                    "bSortable": false
                }, {
                    "data": 6,
                    className: 'text-center',
                    "name": 'created_at',
                    "bSortable": true
                }, {
                    "data": 7,
                    className: 'text-center last_td_action mob-show_div',
                    "bSortable": false
                }],
                "ajax": {
                    "url": window.site_url + "/powerpanel/blocked-ips/get-list", // ajax source
                },
                "order": [
                        [6, "desc"]
                    ] // set first column as a default sort by asc
            }
        });
        $(document).on('keyup', '#searchfilter', function(e) {
            e.preventDefault();
            var action = $('#searchfilter').val();
            if (action.length >= 2) {
                //$.cookie('ContactLeadsearch',action);				
                grid.setAjaxParam("customActionType", "group_action");
                grid.setAjaxParam("searchValue", action);
                grid.setAjaxParam("id", grid.getSelectedRows());
                grid.getDataTable().ajax.reload();
            } else if (action.length < 1) {
                //$.removeCookie('ContactLeadsearch');
                grid.setAjaxParam("customActionType", "group_action");
                grid.setAjaxParam("searchValue", action);
                grid.setAjaxParam("id", grid.getSelectedRows());
                grid.getDataTable().ajax.reload();
            }
        });
        grid.setAjaxParam("customActionType", "group_action");
        grid.clearAjaxParams();
    }
    return {
        //main function to initiate the module
        init: function() {
            initPickers();
            handleRecords();
        }
    };
}();
jQuery(document).ready(function() {
    $.ajaxSetup({
        headers: {
            'X-CSRF-Token': $('input[name="_token"]').val()
        }
    });
    TableDatatablesAjax.init();
});
jQuery(document).ready(function() {
    $(document).on('click', '.submitaction', function() {
        var value2 = $(this).attr('value2');
        if (value2 == 'N') {
            $("div.ipblock_" + this.id).show();
            $(this).attr('value2', 'Y');
        } else {
            $("div.ipblock_" + this.id).hide();
            $(this).attr('value2', 'N');
        }
    });
    //    $(document).on('click', '.submitformblockip', function () {
    //        $('button[name=blockip]').attr('value2', 'N')
    //        $("button.submitaction").click();
    //        if ($('#formblockip' + this.id)[0].checkValidity() == true) {
    //            var ajaxurl = site_url + '/powerpanel/blocked-ips/updateblockid';
    //            var formData = $('form#formblockip' + this.id).serialize();
    //            $.ajax({
    //                url: ajaxurl,
    //                data: formData,
    //                type: "POST",
    //                dataType: "json",
    //                success: function (pollingdata) {
    //
    //                }
    //            });
    //            $("button.close").click()
    //            $('.alert-success2').show()
    //            $('.alert-success2').append('Block Ip has been successfully updated.')
    //            setTimeout(function () {
    //                $('.alert-success2').hide()
    //            }, 5000)
    //            $('#datatable_ajax').DataTable().ajax.reload();
    //           return false;
    //        }
    //
    ////        var ajaxurl = site_url + '/powerpanel/blocked-ips/updateblockid';
    ////        var formData = $('form#formblockip').serialize();
    ////        $.ajax({
    ////            url: ajaxurl,
    ////            data: formData,
    ////            type: "POST",
    ////            dataType: "json",
    ////            success: function (pollingdata) {
    ////
    ////            }
    ////        });
    ////
    ////        $("button.close").click()
    ////        $('.alert-success2').show()
    ////        $('.alert-success2').append('Block Ip has been successfully updated.')
    ////        setTimeout(function () {
    ////            $('.alert-success2').hide()
    ////        }, 5000)
    ////        $('#datatable_ajax').DataTable().ajax.reload();
    //    });
});
$(document).ready(function() {
    let cookie = clearcookie;
    if (cookie == 'true') {
        $.removeCookie('ContactLeadsearch');
        $('#searchfilter').val('');
    }
});