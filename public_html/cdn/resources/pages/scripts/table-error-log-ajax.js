var TableDatatablesAjax = function () {
	var handleRecords = function () {
		var grid = new Datatable();
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
			},
			loadingMessage: 'Loading...',
			dataTable: { // here you can define a typical datatable settings from http://datatables.net/usage/options 
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
					"class": 'text-center td_checker',
					'bSortable': false
				}, {
					"data": 1,
					"class": 'text-left',
					'name': 'varTitle'
				}, {
					"data": 2,
					"class": 'text-center',
					'bSortable': false
				}, {
					"data": 3,
					"class": 'text-center',
					'name': 'created_at'
				}, {
					"data": 4,
					"class": 'text-center',
					'bSortable': false
				}],
				"ajax": {
					"url": window.site_url + "/powerpanel/error-logs/get_list", // ajax source
				},
				"order": [
					[3, "desc"]
				] // set first column as a default sort by asc
			}
		});
		//This code for search filter
		$(document).on('keyup', '#searchfilter', function (e) {
			e.preventDefault();
			var action = $('#searchfilter').val();
			if (action.length >= 2) {
				//$.cookie('EmailLogsearch',action);				
				grid.setAjaxParam("customActionType", "group_action");
				grid.setAjaxParam("searchValue", action);
				grid.setAjaxParam("id", grid.getSelectedRows());
				grid.getDataTable().ajax.reload();
			} else if (action.length < 1) {
				//$.removeCookie('EmailLogsearch');
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
		//main function to initiate the module
		init: function () {
        	$.fn.DataTable.ext.pager.numbers_length = 4;
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