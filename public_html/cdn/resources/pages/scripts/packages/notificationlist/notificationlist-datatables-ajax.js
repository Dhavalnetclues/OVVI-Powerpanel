var grid = "";
var grid1 = "";
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
        var ip = '';
        var totalRec;
        grid.init({
            src: $("#datatable_ajax"),
            onSuccess: function (grid, response) {
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

                // grid:        grid object
                // response:    json object of server side ajax response
                // execute some code after table records loaded
                // get all typeable inputs
                totalRec = response.recordsTotal;
            },
            onError: function (grid) {
                // execute some code on network or other general error
            },
            onDataLoad: function (grid) {
                // execute some code on ajax data load
            },
            loadingMessage: 'Loading...',
            dataTable: {// here you can define a typical datatable settings from http://datatables.net/usage/options
                // Uncomment below line("dom" parameter) to fix the dropdown overflow issue in the datatable cells. The default datatable layout
                // setup uses scrollable div(table-scrollable) with overflow:auto to enable vertical scroll(see: assets/global/scripts/datatable.js).
                // So when dropdowns used the scrollable div should be removed.
                //"dom": "<'row'<'col-md-8 col-sm-12'pli><'col-md-4 col-sm-12'<'table-group-actions pull-right'>>r>t<'row'<'col-md-8 col-sm-12'pli><'col-md-4 col-sm-12'>>",
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
                    className: ' text-center',
                    'bSortable': false
                }, {
                    "data": 1,
                    className: 'text-left Pages_title_P_1 mob-show_div',
                    'name': 'varTitle',
                    'bSortable': false
                }, {
                    "data": 2,
                    className: 'text-left Pages_sdate_P_3',
                    "name": 'dtDateTime',
                    'bSortable': false
                }, {
                    "data": 3,
                    className: 'text-center form-switch Pages_publish_P_3',
                    'name': 'publish',
                    'bSortable': false
                }, {
                    "data": 4,
                    className: 'text-center Pages_dactions_P_4 last_td_action mob-show_div',
                    'bSortable': true
                } ],
                "ajax": {
                    "url": window.site_url + "/powerpanel/notificationlist/get_list", // ajax source
                },
                "order": [
                    [4, "desc"]
                ]// set first column as a default sort by asc
            }
        });

        $(document).on('keyup', '#searchfilter', function(e) {
            e.preventDefault();
            var action = $('#searchfilter').val();
            if (action.length >= 2) {
                $.cookie('notificationlistsearch', action);
                grid.setAjaxParam("customActionType", "group_action");
                grid.setAjaxParam("searchValue", action);
                grid.setAjaxParam("id", grid.getSelectedRows());
                grid.getDataTable().ajax.reload();
            } else {
                $.removeCookie('notificationlistsearch');
                grid.setAjaxParam("customActionType", "group_action");
                grid.setAjaxParam("searchValue", action);
                grid.setAjaxParam("id", grid.getSelectedRows());
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
                                var ajaxurl = window.site_url + "/powerpanel/notificationlist/ExportRecord" + ip;
                                window.location = ajaxurl;
                                grid.getDataTable().ajax.reload();
                            } else {
                                $('#noSelectedRecords').modal('show');
                            }
                        }
                    } else {
                        $('#selected_records').modal('hide');
                        var ajaxurl = window.site_url + "/powerpanel/notificationlist/ExportRecord";
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
            initPickers();
            handleRecords();
        }
    };
}();

$(window).on('load', function() {
	var queryString = window.location.search;
	var urlParams = new URLSearchParams(queryString);
	var sterm = urlParams.get('term');
	if(urlParams.has('term')){
		$('.filter-search').addClass('visible');
    $('#searchfilter').val(sterm);
    $('#searchfilter').trigger('keyup');
	}else{
		if ($.cookie('notificationlistsearch')) {
        $('#searchfilter').val($.cookie('notificationlistsearch'));
        $('#searchfilter').trigger('keyup');
    }
	}

    /*let urlParams = new URLSearchParams(window.location.search);*/
    if(urlParams.has('category')){
	    let category = urlParams.get('category');
	    if (category != '' && category != null) {
	        $("#category").val(category);
	        $('#category').select2();
	        getCategoryData();
	    }
	  }

});

jQuery(document).ready(function () {

    TableDatatablesAjax.init();
    if (!showChecker) {
        grid.getDataTable().column(0).visible(false);
    } else {
        grid.getDataTable().column(0).visible(true);
    }
});

