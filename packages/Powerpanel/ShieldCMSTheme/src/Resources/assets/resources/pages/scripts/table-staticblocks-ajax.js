var grid = "";
var TableDatatablesAjax = function () {
	var handleRecords = function () {
		 	grid = new Datatable();
			grid.init({
				src: $("#static_blocks_datatable_ajax"),
				onSuccess: function (grid, response) {
				if(response.recordsTotal<1){$('.deleteMass').hide();}else{$('.deleteMass').show();}
					// grid:        grid object
					// response:    json object of server side ajax response
					// execute some code after table records loaded
				},
				onError: function (grid) {
					// execute some code on network or other general error  
				},
				onDataLoad: function(grid) {
					$('.make-switch').bootstrapSwitch();
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
					"pageLength": 10, // default record count per page
					//Code for sorting 
					"serverSide": true,
					"columns": [{
							"data": 0, 
							className:'text-center',
							"bSortable":false
						},{
							"data": 1, 
							className:'text-left',
							'name': 'varTitle'
						},{
							"data": 2, 
							className:'text-center',
							"bSortable":false
						},{
							"data": 3, 
							className:'text-right',
							'name': 'created_at'
						},{
							"data": 4,
							className:'text-center publish_switch',
							'bSortable': false
						},{
							"data": 5, 
							className:'text-right',
							'bSortable': false
						}],
						"ajax": {
							"url": window.site_url+"/powerpanel/static-block/get_list", // ajax source
						},								
						"order": [
							[3, "desc"]
						],
				}
			});
			$(document).on("switchChange.bootstrapSwitch",".publish",function(event, state){
				//e.preventDefault();
				var controller = $(this).data('controller');
				var alias = $(this).data('alias');
				var val = $(this).data('value');
				var url = site_url+'/'+controller+'/publish';
				$.ajax({
					url: url,
					data: { alias:alias, val:val},
					type: "POST",         
					dataType: "HTML",        
					success: function(data) {
						 grid.getDataTable().ajax.reload(null, false);
					},
					error: function() {
						console.log('error!');
					}                                 
				});      
			});

		$(document).on('change', '#statusfilter', function (e) {
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
		//This code for search filter
		$(document).on('keyup', '#searchfilter', function (e) {
			e.preventDefault();
			var action = $('#searchfilter').val();
			if (action.length>=2) {				
				$.cookie('StaticBlocksearch',action);				
				grid.setAjaxParam("customActionType", "group_action");
				grid.setAjaxParam("searchValue", action);
				grid.setAjaxParam("id", grid.getSelectedRows());
				grid.getDataTable().ajax.reload();
			} else if(action.length<1) {
				$.removeCookie('StaticBlocksearch');
				grid.setAjaxParam("customActionType", "group_action");
				grid.setAjaxParam("searchValue", action);
				grid.setAjaxParam("id", grid.getSelectedRows());
				grid.getDataTable().ajax.reload();
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
			handleRecords();
		}
	};
}();
$(window).on('load', function() {
		if ($.cookie('StaticBlocksearch')) {
				$('#searchfilter').val($.cookie('StaticBlocksearch'));
				$('#searchfilter').trigger('keyup');
		}
});
jQuery(document).ready(function() {	
	$.ajaxSetup({
		headers:{
			'X-CSRF-Token': $('input[name="_token"]').val()
		}
	});
	TableDatatablesAjax.init();
});

$(document).on('click', '.group-checkable',function(){
	if($(this).prop('checked') == true){
		$('.group-checkable').closest('tbody').find('.chkDelete').prop('checked',true);		
	}else{
		$('.group-checkable').closest('tbody').find('.chkDelete').prop('checked',false);
	}
});

$(document).on('click', '.chkDelete',function(){

	var total = $('.group-checkable').closest('tbody').find('.chkDelete').length;
	var checked = $('.group-checkable').closest('tbody').find('.chkDelete:checked').length;

	if(total == checked){
		$('.chkDelete').closest('tbody').find('.group-checkable').prop('checked',true);		
	}else{
		$('.chkDelete').closest('tbody').find('.group-checkable').prop('checked',false);
	}
});