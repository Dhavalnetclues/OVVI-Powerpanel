console.log("okokokok");

var gridRows = 0;   //-- Main Tab
var grid = '';
var TableDatatablesAjax = function() {
    var handleRecords = function() {
        var action = $('#category').val();
        grid = new Datatable();
        grid.setAjaxParam("catValue", action);
        grid.init({
            src: $("#datatable_ajax"),
            onSuccess: function(grid, response) {
                gridRows = response.recordsTotal;
                if (response.recordsTotal < 1) {
                    $('.deleteMass').hide();
                } else {
                    $('.deleteMass').show();
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
                // execute some code on ajax data load
                // $('.make-switch').bootstrapSwitch();
            },
            loadingMessage: 'Loading...',
            dataTable: { // here you can define a typical datatable settings from http://datatables.net/usage/options
                // Uncomment below line("dom" parameter) to fix the dropdown overflow issue in the datatable cells. The default datatable layout
                // setup uses scrollable div(table-scrollable) with overflow:auto to enable vertical scroll(see: assets/global/scripts/datatable.js).
                // So when dropdowns used the scrollable div should be removed.
                "dom": "t <'gridjs-footer' <'gridjs-pagination'i <'gridjs-pages'p>>>",
                "deferRender": true,
                // "stateSave": true, // save datatable state(pagination, sort, etc) in cookie.
                // "lengthMenu": [
                //     [10, 20, 50, 100],
                //     [10, 20, 50, 100] // change per page values here
                // ],
                "pageLength": 20, // default record count per page
                drawCallback:function(){
                    var $api = this.api();
                    var pages = $api.page.info().pages;
                    var rows = $api.data().length;
                    if(pages<=1){
                        $('.dataTables_info').css('display','none');
                        $('.dataTables_paginate').css('display','none');
                    }
                },
                // Code for sorting
                "serverSide": true,
                "lengthChange": false,
                "pagingType": "simple_numbers",
                "language": {
                    "info": '<div role="status" aria-live="polite" class="gridjs-summary">Showing <b>_START_</b> to <b>_END_</b> of <b>_TOTAL_</b> results</div>', // title="Page 1 of 2"
                },
                "columns": [{
                    "data": 0,
                    "class": 'td_checker',
                    'bSortable': false
                }, {
                    "data": 1,
                    "class": 'text-left',
                    'name': 'intFkEmailType'
                }, {
                    "data": 2,
                    "class": 'text-left',
                    'name': 'varFrom',
                    'bSortable': false
                }, {
                    "data": 3,
                    "class": 'text-left',
                    'name': 'txtTo',
                    'bSortable': false
                }, {
                    "data": 4,
                    "class": 'text-center',
                    'name': 'chrIsSent',
                    'bSortable': false
                }, {
                    "data": 5,
                    "class": 'text-center',
                    'bSortable': false
                }, {
                    "data": 6,
                    "class": 'text-center',
                    'name': 'created_at'
                }],
                "ajax": {
                    "url": window.site_url + "/powerpanel/email-log/get_list", // ajax source
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
                $.cookie('EmailLogsearch', action);
                grid.setAjaxParam("customActionType", "group_action");
                grid.setAjaxParam("searchValue", action);
                grid.setAjaxParam("id", grid.getSelectedRows());
                grid.getDataTable().ajax.reload();
            } else if (action.length < 1) {
                $.removeCookie('EmailLogsearch');
                grid.setAjaxParam("customActionType", "group_action");
                grid.setAjaxParam("searchValue", action);
                grid.setAjaxParam("id", grid.getSelectedRows());
                grid.getDataTable().ajax.reload();
            }
        });

        $(document).on('click', '.emaillog', function(e) {
            var emaillogpage_id = this.id;
            $.ajax({
                url: site_url + '/powerpanel/email-log/ajax',
                data: {
                    emaillogpage_id: emaillogpage_id
                },
                type: "POST",
                dataType: "json",
                success: function(data) {
                    var html = '';
                    if (data != null && data != '') {
                        html += '<div class="modal-dialog">';
                        html += '<div class="modal-vertical">';
                        html += '<div class="modal-content">';
                        html += '<div class="modal-header">';
                        html += '<button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>';
                        html += '<h3 class="modal-title">' + data.txt_subject + '</h3>';
                        html += '</div>';
                        html += '<div class="modal-body">';
                        html += '<strong>To: </strong>' + data.txt_to + '',
                            html += '</br>',
                            html += '<strong>Date: </strong>' + data.date + '',
                            html += '</br>',
                            html += '<strong>Email Details:</strong>',
                            html += '<div style="height:15px;clear:both"></div>',
                            html += '<div style="margin:auto;">' + data.txt_body + '</div>',
                            html += '</div>';
                        html += '</div>';
                        html += '</div>';
                        html += '</div>';
                        $('.DetailsEmailLog').html(html);
                        $('.DetailsEmailLog').modal('show');
                    }
                },
                error: function() {
                    console.log('error!');
                }
            });
        });

        //This code for email type filter
        $(document).on('change', '#emailtypefilter', function(e) {
            e.preventDefault();
            var action = $('#emailtypefilter').val();
            if (action != "") {
                grid.setAjaxParam("customActionType", "group_action");
                grid.setAjaxParam("emailtypeValue", action);
                grid.setAjaxParam("id", grid.getSelectedRows());
                grid.getDataTable().ajax.reload();
            } else {
                grid.setAjaxParam("customActionType", "group_action");
                grid.setAjaxParam("emailtypeValue", action);
                grid.setAjaxParam("id", grid.getSelectedRows());
            }
        });

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




jQuery(document).ready(function() {
    $.ajaxSetup({
        headers: {
            'X-CSRF-Token': $('input[name="_token"]').val()
        }
    });
    TableDatatablesAjax.init();
});

$(document).ready(function() {
    let cookie = clearcookie;
    if (cookie == 'true') {
        $.removeCookie('EmailLogsearch');
        $('#searchfilter').val('');
    }
});