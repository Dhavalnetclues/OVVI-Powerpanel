var TableDatatablesAjax = function() {
		var initPickers = function() {
				//init date pickers
				$('.date-picker').datepicker({
						rtl: App.isRTL(),
						autoclose: true
				});
		}
		var handleRecords = function() {
				var grid = new Datatable(); //advertisesearch
				if(!tableState){
				  $.removeCookie('advertisesearch');
				  $("#datatable_ajax").DataTable().state.clear();
		      $("#datatable_ajax").DataTable().destroy();
		    }
		    if (tableState) {
		    	  if($.cookie('advertisesearch')) {
					      var advertisesearch = $.cookie('advertisesearch');
	                $('#searchfilter').val(advertisesearch);
	                grid.setAjaxParam("searchValue", advertisesearch);
	                $('#searchfilter').trigger('keyup');
	           }
			  }
				grid.init({
						src: $("#datatable_ajax"),
						onSuccess: function(grid, response) {
								if (response.recordsTotal < 1) {
										$('.deleteMass').hide();
								} else {
										$('.deleteMass').show();
								}
								// grid:        grid object
								// response:    json object of server side ajax response
								// execute some code after table records loaded
						},
						onError: function(grid) {
								// execute some code on network or other general error  
						},
						onDataLoad: function(grid) {
								// execute some code on ajax data load
								// $('.make-switch').bootstrapSwitch();
						},
						loadingMessage: 'Loading...',
						dataTable: {
								"deferRender": true,
								"stateSave": true,
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
										"name": 'varTitle'
								}, {
										"data": 2,
										"bSortable": false,
										className: 'text-center'
								}, {
										"data": 3,
										"name": 'dtStartDateTime',
										className: 'text-center'
								}, {
										"data": 4,
										"name": 'dtEndDateTime',
										className: 'text-center'
								}, {
										"data": 5,
										"bSortable": false,
										className: 'text-center'
								}, {
										"data": 6,
										"bSortable": false,
										className: 'text-center'
								}, {
										"data": 7,
										"bSortable": false,
										className: 'text-center form-switch'
								},{
										"data": 8,
										"bSortable": false,
										className: 'text-right'
								}],
								"ajax": {
										"url": window.site_url + "/powerpanel/advertise/get_list", // ajax source
								},
								"order": [
										[3, "desc"]
								]
						}
				});
				$(document).on("switchChange.bootstrapSwitch",".publish",function(event, state){
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
								success: function(data) {
										grid.getDataTable().ajax.reload(null,false);
								},
								error: function() {
										console.log('error!');
								}
						});
				});
				$(document).on('change', '#statusfilter', function(e) {
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
				
				$(document).on('click', '#refresh', function(e) {
						$('#start_date').val('');
						$('#end_date').val('');
						grid.setAjaxParam("rangeFilter", '');
						grid.getDataTable().ajax.reload();
				});
				$(document).on('keyup', '#searchfilter', function(e) {
						e.preventDefault();
						var action = $('#searchfilter').val();
						if (action.length >= 2) {
								$.cookie('advertisesearch',action);				
								grid.setAjaxParam("customActionType", "group_action");
								grid.setAjaxParam("searchValue", action);
								grid.setAjaxParam("id", grid.getSelectedRows());
								grid.getDataTable().ajax.reload();
						} else if (action.length < 1) {
								$.removeCookie('advertisesearch');
								grid.setAjaxParam("customActionType", "group_action");
								grid.setAjaxParam("searchValue", action);
								grid.setAjaxParam("id", grid.getSelectedRows());
								grid.getDataTable().ajax.reload();
						}
				});
				$(document).on('click', '#advertiseRange', function(e) {
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
				grid.setAjaxParam("customActionType", "group_action");
				grid.clearAjaxParams();
				grid.getDataTable().columns().iterator('column', function(ctx, idx) {
						$(grid.getDataTable().column(idx).header()).append('<span class="sort-icon"/>');
				});
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