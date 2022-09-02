var TableDatatablesAjax = function () {
	var handleRecords = function () {
		var grid = new Datatable();
		  if(!tableState){
	      $.removeCookie('cmsPagesSearch');
	      $("#email_log_datatable_ajax").DataTable().state.clear();
	      $("#email_log_datatable_ajax").DataTable().destroy();
	    }
      if(tableState) {
		   if($.cookie('cmsPagesSearch')){
		   	 var cmsPagesSearch = $.cookie('cmsPagesSearch');
		      $('#searchfilter').val(cmsPagesSearch);
		      grid.setAjaxParam("searchValue", cmsPagesSearch);
          $('#searchfilter').trigger('keyup');
		    }
		    if($.cookie('customActionName') && $.cookie('customActionName') != 'null' && $.cookie('customActionName') != ''){
					$('#statusfilter').val($.cookie('customActionName'));
					grid.setAjaxParam("customActionName", $.cookie('customActionName'));
					$('#statusfilter').trigger('change');	
				}
		  }

			grid.init({
				src: $("#email_log_datatable_ajax"),
				onSuccess: function (grid, response) 
				{
					if(response.recordsTotal<1){
						$('.deleteMass').hide();
					}else{
						$('.deleteMass').show();
					}
					$('[data-bs-toggle="tooltip"]').tooltip();
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
				dataTable: { 
					// here you can define a typical datatable settings from http://datatables.net/usage/options 
					// Uncomment below line("dom" parameter) to fix the dropdown overflow issue in the datatable cells. The default datatable layout
					// setup uses scrollable div(table-scrollable) with overflow:auto to enable vertical scroll(see: assets/global/scripts/datatable.js). 
					// So when dropdowns used the scrollable div should be removed. 
					//"dom": "<'row'<'col-md-8 col-sm-12'pli><'col-md-4 col-sm-12'<'table-group-actions pull-right'>>r>t<'row'<'col-md-8 col-sm-12'pli><'col-md-4 col-sm-12'>>",
					"deferRender":true,
         	"stateSave": true, //save datatable state(pagination, sort, etc) in cookie.
					"lengthMenu": [
						[10, 20, 50, 100],
						[10, 20, 50, 100] // change per page values here
					],
					"pageLength": 100, // default record count per page
					//Code for sorting 
					"serverSide": true,
					"columns": [
						{"data": 0, 'class':'text-center', 'bSortable': false},
						{"data": 1, 'class':'text-left','name' : 'varTitle'},
						{"data": 2, 'class':'text-center','bSortable': false},
						{"data": 3, 'class':'text-center','bSortable': false},
						{"data": 4, 'class':'text-center','bSortable': false},
						{"data": 5, 'class':'text-center publish_switch','bSortable': false},
						{"data": 6, 'class':'text-right','bSortable': false},
					],
					"ajax": {
						"url": window.site_url+"/powerpanel/pages/get_list", // ajax source
					},
					"order": [
						[1, "desc"]
					]// set first column as a default sort by asc
				}
			});
		//This code for search filter
		$(document).on('keyup', '#searchfilter', function (e) 
		{
			e.preventDefault();
			var action = $('#searchfilter').val();
			if (action.length >= 2) 
			{				
				$.cookie('cmsPagesSearch',action);	
				grid.setAjaxParam("customActionType", "group_action");
				grid.setAjaxParam("searchValue", action);
				grid.setAjaxParam("id", grid.getSelectedRows());
				grid.getDataTable().ajax.reload();
			} else if(action.length < 1) {
				$.removeCookie('cmsPagesSearch');
				grid.setAjaxParam("customActionType", "group_action");
				grid.setAjaxParam("searchValue", action);
				grid.setAjaxParam("id", grid.getSelectedRows());
				grid.getDataTable().ajax.reload();
			}
			
		});

		//This code for email type filter
		$(document).on('change', '#statusfilter', function (e) 
		{
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
		
		$(document).on("switchChange.bootstrapSwitch",".publish",function(event, state)
		{
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
					grid.getDataTable().ajax.reload(null,false);
				},
				error: function() {
					console.log('error!');
				}                                 
			});	

		});

		grid.setAjaxParam("customActionType", "group_action");		
		grid.clearAjaxParams();
	 	grid.getDataTable().columns().iterator('column', function (ctx, idx) {
        $(grid.getDataTable().column(idx).header() ).append('<span class="sort-icon"/>');
    });

	}
	return {
		//main function to initiate the module
		init: function () {
			handleRecords();
		}
	};
}();

// $(window).on('load',function()
// {
//   if($.cookie('cmsPagesSearch')){
//     $('#searchfilter').val($.cookie('cmsPagesSearch'));
//     $('#searchfilter').trigger('keyup');  
//   }
// });

jQuery(document).ready(function() {
	$.ajaxSetup({
		headers:{
			'X-CSRF-Token': $('input[name="_token"]').val()
		}
	});  
	TableDatatablesAjax.init();
});