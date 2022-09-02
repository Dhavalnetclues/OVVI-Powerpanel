var TableDatatablesAjax = function () {
	var initPickers = function () {
		//init date pickers
	}
	var handleRecords = function () {
		var grid = new Datatable();
		var ip='';
		var totalRec;
			grid.init({
				src: $("#datatable_ajax"),
				onSuccess: function (grid, response) {
				if(response.recordsTotal<1){$('.deleteMass').hide();}else{$('.deleteMass').show();}
				if(response.recordsTotal<1){$('.ExportRecord').hide();}else{$('.ExportRecord').show();}
					// grid:        grid object
					// response:    json object of server side ajax response
					// execute some code after table records loaded
				},
				onError: function (grid) {
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
					"stateSave": true, // save datatable state(pagination, sort, etc) in cookie.
					"lengthMenu": [
						[10, 20, 50, 100],
						[10, 20, 50, 100] // change per page values here
					],
					"pageLength": 100, // default record count per page
					//Code for sorting
					"serverSide": true,
					"columns": [
						{"data": 0, className:'text-center', "bSortable":false},
						/*{"data": 1, className:'text-left',"name":'name'},*/
						{"data": 1, className:'text-left',"bSortable":false },
						{"data": 2, className:'text-center',"name":'chrSubscribed'},
						{"data": 3, className:'text-center',"bSortable":false },
						{"data": 4, className:'text-center',"name":'created_at'}
					],
					"ajax": {
						"url": window.site_url+"/powerpanel/newsletter-lead/get_list", // ajax source
					}
					, "order": [
						[4, "desc"]
					],
				}
			});
			//This code for search filter
			$(document).on('keyup', '#searchfilter', function (e) {
				e.preventDefault();
				var action = $('#searchfilter').val();
				if (action.length>=2) {				
					$.cookie('subscriptionSearch',action);				
					grid.setAjaxParam("customActionType", "group_action");
					grid.setAjaxParam("searchValue", action);
					grid.setAjaxParam("id", grid.getSelectedRows());
					grid.getDataTable().ajax.reload();
				} else if(action.length<1) {
					$.removeCookie('subscriptionSearch');
					grid.setAjaxParam("customActionType", "group_action");
					grid.setAjaxParam("searchValue", action);
					grid.setAjaxParam("id", grid.getSelectedRows());
					grid.getDataTable().ajax.reload();
				}
			});
			$ ('#ExportRecord').on('click',function(e) {
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
									ip = '?'+$('input[name="delete[]"]:checked').serialize()+'&'+'export_type'+'='+exportRadioVal;
									var ajaxurl = window.site_url+"/powerpanel/newsletter-lead/ExportRecord"+ip;
									window.location = ajaxurl;
									grid.getDataTable().ajax.reload();
								} else {
									$('#noSelectedRecords').modal('show');
								}
							}
						} else {
							$('#selected_records').modal('hide');
							var ajaxurl = window.site_url+"/powerpanel/newsletter-lead/ExportRecord";
							window.location = ajaxurl;
						}
					}
				}
			});
			// handle group actionsubmit button click
			grid.getTableWrapper().on('click', '.table-group-action-submit', function (e) {
				e.preventDefault();
				var action = $(".table-group-action-input", grid.getTableWrapper());
				if (action.val() != "" && grid.getSelectedRowsCount() > 0) {
					grid.setAjaxParam("customActionType", "group_action");
					grid.setAjaxParam("customActionName", action.val());
					grid.setAjaxParam("id", grid.getSelectedRows());
					grid.getDataTable().ajax.reload();
					grid.clearAjaxParams();
				} else if (action.val() == "") {
					App.alert({
						type: 'danger',
						icon: 'warning',
						message: 'Please select an action',
						container: grid.getTableWrapper(),
						place: 'prepend'
					});
				} else if (grid.getSelectedRowsCount() === 0) {
					App.alert({
						type: 'danger',
						icon: 'warning',
						message: 'No record selected',
						container: grid.getTableWrapper(),
						place: 'prepend'
					});
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
			init: function () {
				initPickers();
				handleRecords();
			}
		};
}();

  

$(window).on('load',function(){
  if($.cookie('subscriptionSearch')){
    $('#searchfilter').val($.cookie('subscriptionSearch'));
    $('#searchfilter').trigger('keyup');  
  }
});
$(document).on('click', '#refresh', function(e) {
      if($.cookie('subscriptionSearch')){
		    $('#searchfilter').val('');
		    $('#searchfilter').trigger('keyup');  
		  }      
});
jQuery(document).ready(function() {
	$.ajaxSetup({
		headers: {
			'X-CSRF-Token': $('input[name="_token"]').val()
		}
	});
	TableDatatablesAjax.init();		
});