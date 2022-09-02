var TableDatatablesAjax = function() {
    var handleRecords = function() {
        var grid = new Datatable();
        //StaticBlocksearch
        if(!tableState){
              $.removeCookie('StaticBlocksearch');
          $("#static_blocks_datatable_ajax").DataTable().state.clear();
          $("#static_blocks_datatable_ajax").DataTable().destroy();
        }
        if (tableState) {
             if($.cookie('customActionName')) {
              $('#statusfilter').val($.cookie('customActionName'));
              grid.setAjaxParam("customActionName", $.cookie('customActionName'));
              $('#statusfilter').trigger('change'); 
          } 
            if($.cookie('StaticBlocksearch')) {
              var StaticBlocksearch = $.cookie('StaticBlocksearch');
              $('#searchfilter').val(StaticBlocksearch);
              grid.setAjaxParam("searchValue", StaticBlocksearch);
              $('#searchfilter').trigger('keyup');
           }            
        }

        grid.init({
            src: $("#static_blocks_datatable_ajax"),
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
                $('.make-switch').bootstrapSwitch();
                expandcollapsepanel($('#onload_1'),'tasklisting1','mainsingnimg1',1);
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
                "columns": [{
                        "data": 0,
                        className: 'text-center',
                        "bSortable": false
                    }, {
                        "data": 1,
                        className: 'text-left',
                        'name': 'varTitle'
                    },
                    // {"data": 2, className:'text-center',"bSortable":false},
                    //{"data": 1, className:'text-left',"bSortable":false},
                    {
                        "data": 2,
                        className: 'text-center',
                        "bSortable": false
                    }, {
                        "data": 3,
                        className: 'text-right',
                        'name': 'created_at'
                    }, {
                        "data": 4,
                        className: 'text-center publish_switch',
                        'bSortable': false
                    }, {
                        "data": 5,
                        className: 'text-right',
                        'bSortable': false
                    }
                ],
                "ajax": {
                    "url": window.site_url + "/powerpanel/static-block/get_list", // ajax source
                },
                "order": [
                    [3, "desc"]
                ], // set first column as a default sort by asc
            }
        });
        $(document).on("switchChange.bootstrapSwitch", ".publish", function(event, state) {
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
                    //console.log(alias);
                    grid.getDataTable().ajax.reload(null, false);
                    //$("#tasklisting"+alias).css('display','block');
                    // $("#tasklisting18").css('display','block');
                },
                error: function() {
                    console.log('error!');
                }
            });
        });
        // $(document).on("switchChange.bootstrapSwitch",".dataTableStaticBlock .publish",function(event, state){
        // 	//e.preventDefault();
        // 	var controller = $(this).data('controller');
        // 	var alias = $(this).data('alias');
        // 	var val = $(this).data('value');
        // 	var url = site_url+'/'+controller+'/publish';
        // 	$.ajax({
        // 		url: url,
        // 		data: { alias:alias, val:val},
        // 		type: "POST",         
        // 		dataType: "HTML",        
        // 		success: function(data) {
        // 			 //console.log(alias);
        // 			 grid.getDataTable().ajax.reload(null, false);
        // 			 //$("#tasklisting"+alias).css('display','block');
        // 			// $("#tasklisting18").css('display','block');
        // 		},
        // 		error: function() {
        // 			console.log('error!');
        // 		}                                 
        // 	});      
        // });
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
        //This code for search filter
        $(document).on('keyup', '#searchfilter', function(e) {
            e.preventDefault();
            var action = $('#searchfilter').val();
            if (action.length >= 2) {
                $.cookie('StaticBlocksearch', action);
                grid.setAjaxParam("customActionType", "group_action");
                grid.setAjaxParam("searchValue", action);
                grid.setAjaxParam("id", grid.getSelectedRows());
                grid.getDataTable().ajax.reload();
            } else if (action.length < 1) {
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
        init: function() {
            handleRecords();
        }
    };
}();

function expandcollapsepanel(object, panelid, elementid, id) 
{
    var panelid = 'tasklisting' + id;
    var elementid = 'mainsingnimg' + id;
    var id = id;
    var attr = $('#' + elementid).attr('class');
    if (attr == 'fa fa-plus') {
        $.ajax({
            type: "POST",
            url: "static-block/getChildData",
            data: "panelid=" + panelid + "&elementid=" + elementid + "&id=" + id,
            success: function(data) {
                var itemID = 'tasklisting' + id;
                if ($('#' + itemID).length == 0) {
                    var treq = $(object).closest('tr');
                    $(treq).after('<tr id="tasklisting' + id + '" class="odd" role="row"><td align="left" colspan="30" class=""><div id="ChildDiv' + id + '"></div></td></tr>');
                }
                document.getElementById(itemID).style.display = '';
                document.getElementById('ChildDiv' + id).innerHTML = data;
                $('#' + elementid).removeClass(attr);
                $("#" + elementid).addClass("fa fa-minus");
                $('.make-switch').bootstrapSwitch();
            }
        });
    } 
   if (attr == 'fa fa-minus') {
    //else {
        var elementid = 'mainsingnimg' + id;
        var attr = $('#' + elementid).attr('class');
        var itemID = 'tasklisting' + id;
        document.getElementById('ChildDiv' + id).innerHTML = '';
        $('#' + elementid).removeClass(attr);
        $("#" + elementid).addClass("fa fa-plus ");
        document.getElementById(itemID).style.display = 'none';
    }
}
	
// $(window).on('load', function() {
//     if ($.cookie('StaticBlocksearch')) {
//         $('#searchfilter').val($.cookie('StaticBlocksearch'));
//         $('#searchfilter').trigger('keyup');
//     }
// });

jQuery(document).ready(function() {
    $.ajaxSetup({
        headers: {
            'X-CSRF-Token': $('input[name="_token"]').val()
        }
    });
    TableDatatablesAjax.init();
});