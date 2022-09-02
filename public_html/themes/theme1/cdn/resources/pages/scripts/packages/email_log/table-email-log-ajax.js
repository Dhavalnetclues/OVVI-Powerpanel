var TableDatatablesAjax = function() {
		var handleRecords = function() {
				var grid = new Datatable();
				if(!tableState){
				  $.removeCookie('EmailLogsearch');
				  $("#datatable_ajax").DataTable().state.clear();
		      $("#datatable_ajax").DataTable().destroy();
		    }
		    if (tableState) {
		    	  if($.cookie('EmailLogsearch')) {
					      var EmailLogsearch = $.cookie('EmailLogsearch');
	                $('#searchfilter').val(EmailLogsearch);
	                grid.setAjaxParam("searchValue", EmailLogsearch);
	                $('#searchfilter').trigger('keyup');
	           }  	        
			  }
				grid.init({
						src: $("#email_log_datatable_ajax"),
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
						},
						loadingMessage: 'Loading...',
						dataTable: { // here you can define a typical datatable settings from http://datatables.net/usage/options 
								// Uncomment below line("dom" parameter) to fix the dropdown overflow issue in the datatable cells. The default datatable layout
								// setup uses scrollable div(table-scrollable) with overflow:auto to enable vertical scroll(see: assets/global/scripts/datatable.js). 
								// So when dropdowns used the scrollable div should be removed. 
								//"dom": "<'row'<'col-md-8 col-sm-12'pli><'col-md-4 col-sm-12'<'table-group-actions pull-right'>>r>t<'row'<'col-md-8 col-sm-12'pli><'col-md-4 col-sm-12'>>",
								"deferRender": true,
								"stateSave": true,  // save datatable state(pagination, sort, etc) in cookie.
								"lengthMenu": [
										[10, 20, 50, 100],
										[10, 20, 50, 100] // change per page values here
								],
								"pageLength": 100, // default record count per page
								//Code for sorting 
								"serverSide": true,
								"columns": [{
										"data": 0,
										className: 'td_checker',
										'bSortable': false
								}, {
										"data": 1,
										className: 'text-left',
										'name': 'intFkEmailType'
								}, {
										"data": 2,
										className: 'text-left',
										'name': 'varFrom',
										'bSortable': false
								}, {
										"data": 3,
										className: 'text-left',
										'name': 'txtTo',
										'bSortable': false
								},{
										"data": 4,
										className: 'text-center',
										'name': 'chrIsSent'
								}, {
										"data": 5,
										className: 'text-center',
										'bSortable': false
								}, {
										"data": 6,
										className: 'text-center',
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
				//This code for search filter
				$(document).on('keyup', '#searchfilter', function(e) {
						e.preventDefault();
						var action = $('#searchfilter').val();
						if (action.length >= 2) {
								$.cookie('EmailLogsearch',action);				
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