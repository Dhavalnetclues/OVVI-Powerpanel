var gridRows = 0;
var grid = "";
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
            dataTable: {// here you can define a typical datatable settings from http://datatables.net/usage/options 
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
                        className: 'td_checker mob-show_div',
                        "bSortable": false
                    }, {
                        "data": 1,
                        className: 'text-left mob-show_div',
                        'name': 'name'
                    }, {
                        "data": 2,
                        className: 'text-center',
                        'name': 'chrIsAdmin'
                    },{
                        "data": 3,
                        className: 'text-center',
                        'name': 'updated_at'
                    }, {
                        "data": 4,
                        className: 'text-right last_td_action mob-show_div',
                        'bSortable': false
                    }, ],
                "ajax": {
                    "url": window.site_url + "/powerpanel/roles/get_list", // ajax source
                },
                "order": [
                    [3, "desc"]
                ] // set first column as a default sort by asc
            }
        });
        // handle group actionsubmit button click
        $(document).on('keyup', '#searchfilter', function (e) {
            e.preventDefault();
            var action = $('#searchfilter').val();
            if (action.length >= 2) {
                //$.cookie('Rolesearch',action);              
                grid.setAjaxParam("customActionType", "group_action");
                grid.setAjaxParam("searchValue", action);
                grid.setAjaxParam("id", grid.getSelectedRows());
                grid.getDataTable().ajax.reload();
            } else if (action.length < 1) {
                //$.removeCookie('Rolesearch');
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
            initPickers();
            handleRecords();
        }
    };
}();
/*$(window).on('load',function(){
 if($.cookie('Rolesearch')){
 $('#searchfilter').val($.cookie('Rolesearch'));
 $('#searchfilter').trigger('keyup');    
 }
 });*/
jQuery(document).ready(function () {
    TableDatatablesAjax.init();
});