var gridRows = 0;
var grid;
var TableDatatablesAjax = function() {
		var initPickers = function() {}
		var handleRecords = function() {
				grid = new Datatable();
				if(!tableState){
				  $.removeCookie('testimonialSearch');
		      $("#datatable_ajax").DataTable().state.clear();
		      $("#datatable_ajax").DataTable().destroy();
		    }
		    if (tableState) {
		    	  if($.cookie('testimonialSearch')) {
					      var testimonialSearch = $.cookie('testimonialSearch');
	                $('#searchfilter').val(testimonialSearch);
	                grid.setAjaxParam("searchValue", testimonialSearch);
	                $('#searchfilter').trigger('keyup');
	           }       
			  }
				grid.init({
						src: $("#testimonial_datatable_ajax"),
						onSuccess: function(grid, response) {
								if (response.recordsTotal < 1) {
										$('.deleteMass').hide();
								} else {
										$('.deleteMass').show();
								}
						},
						onError: function(grid) {},
						onDataLoad: function(grid) {
								// execute some code on ajax data load
								if ($('.pagination-panel .prev').hasClass('disabled')) {
										$("#testimonial_datatable_ajax tbody tr:first").find('.moveUp').hide();
								}
								if ($('.pagination-panel .next').hasClass('disabled')) {
										$("#testimonial_datatable_ajax tbody tr:last").find('.moveDwn').hide();
								}
								// $('.make-switch').bootstrapSwitch();
						},
						loadingMessage: 'Loading...',
						dataTable: {
								"deferRender": true,
								"stateSave": true,
								"lengthMenu": [
										[10, 20, 50, 100],
										[10, 20, 50, 100]
								],
								"pageLength": 100,
								"serverSide": true,
								"columns": [{
										"data": 0,
										className: 'td_checker',
										'bSortable': false
								},{
										"data": 1,
										className: 'text-left',
										'name': 'varTitle'
								},{
										"data": 2,
										className: 'text-center',
										'bSortable': false
								},{
										"data": 3,
										className: 'text-center',
										'bSortable': false
								},{
										"data": 4,
										className: 'text-center',
										'name': 'dtStartDateTime'
								},{
										"data": 5,
										className: 'text-center form-switch',
										'bSortable': false
								},{
										"data": 6,
										className: 'text-right',
										'bSortable': false
								}],
								"ajax": {
										"url": window.site_url + "/powerpanel/testimonial/get_list",
								},
								"order": [
										[4, "desc"]
								]
						}
				});

				$(document).on('keyup', '#searchfilter', function(e) {
						e.preventDefault();
						var action = $('#searchfilter').val();
            if (action.length >= 2) {
                $.cookie('testimonialSearch',action);      
                grid.setAjaxParam("customActionType", "group_action");
                grid.setAjaxParam("searchValue", action);
                grid.setAjaxParam("id", grid.getSelectedRows());
                grid.getDataTable().ajax.reload();
            } else if (action.length < 1) {
                $.removeCookie('testimonialSearch');
                grid.setAjaxParam("customActionType", "group_action");
                grid.setAjaxParam("searchValue", action);
                grid.setAjaxParam("id", grid.getSelectedRows());
                grid.getDataTable().ajax.reload();
            }						
				});
				$(document).on('click', '#dateFilter', function(e) {
						var dateValue = $('#testimonialdate').val();
						if (dateValue != "") {
								grid.setAjaxParam("customActionType", "group_action");
								grid.setAjaxParam("dateValue", dateValue);
								grid.setAjaxParam("id", grid.getSelectedRows());
								grid.getDataTable().ajax.reload();
						} else {
								grid.setAjaxParam("customActionType", "group_action");
								grid.setAjaxParam("dateValue", dateValue);
								grid.setAjaxParam("id", grid.getSelectedRows());
						}
				});
				$(document).on('change', '#statusfilter', function(e) {
						e.preventDefault();
						var action = $('#statusfilter').val();
						if (action != "") {
								grid.setAjaxParam("customActionType", "group_action");
								grid.setAjaxParam("statusFilter", action);
								grid.setAjaxParam("id", grid.getSelectedRows());
								grid.getDataTable().ajax.reload();
						} else {
								grid.setAjaxParam("customActionType", "group_action");
								grid.setAjaxParam("statusFilter", action);
								grid.setAjaxParam("id", grid.getSelectedRows());
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
				$(document).on('click', '#resetFilter', function(e) {
					  $('#testimonialdate').val('');
						grid.setAjaxParam("dateValue", null);
						grid.getDataTable().ajax.reload();
				});
				grid.setAjaxParam("customActionType", "group_action");
				grid.clearAjaxParams();
				grid.getDataTable().columns().iterator('column', function(ctx, idx) {
						$(grid.getDataTable().column(idx).header()).append('<span class="sort-icon"/>');
				});
		}
		return {
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
