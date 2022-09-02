var gridRows = 0;
var grid = "";
var TableDatatablesAjax = function () {
    var initPickers = function () {
    }
    var handleRecords = function () {
        var action = $('#category_id').val();
        grid = new Datatable();
        grid.setAjaxParam("catValue", action);
        grid.init({
            src: $("#datatable_ajax"),
            onSuccess: function (grid, response) {
                gridRows = response.recordsTotal;
                if (response.recordsTotal < 1) {
                    $('.deleteMass').hide();
                } else {
                    $('.deleteMass').show();
                }
                if (response.newRecordCount > 0) {
                    $('.newcounter').text(response.newRecordCount).show();
                } else {
                    $('.newcounter').hide();
                }
                setTimeout(function(){
                    $.each(settingarray, function (index, value) {
                          if (index == 'P') {
                              $.each(value, function (index, columnid) {
                                  $('#datatable_ajax thead').find('.'+columnid).addClass("hidecolumn");
                                  $("#datatable_ajax tbody").find('tr').each(function (index, value) {
                                      $(this).find('.'+columnid).addClass("hidecolumn");
                                  });
                              });
                          }
                      });   
                }, 1200);
                // grid:        grid object
                // response:    json object of server side ajax response
                // execute some code after table records loaded
            },
            onError: function (grid) {
                // execute some code on network or other general error  
            },
            onDataLoad: function (grid) {
                // execute some code on ajax data load
                if ($('.pagination-panel .prev').hasClass('disabled')) {
                    $("#datatable_ajax tbody tr:first").find('.moveUp').hide();
                }
                if ($('.pagination-panel .next').hasClass('disabled')) {
                    $("#datatable_ajax tbody tr:last").find('.moveDwn').hide();
                }
                $('.make-switch').bootstrapSwitch();
            },
            loadingMessage: 'Loading...',
            dataTable: {// here you can define a typical datatable settings from http://datatables.net/usage/options 
                // Uncomment below line("dom" parameter) to fix the dropdown overflow issue in the datatable cells. The default datatable layout
                // setup uses scrollable div(table-scrollable) with overflow:auto to enable vertical scroll(see: assets/global/scripts/datatable.js). 
                // So when dropdowns used the scrollable div should be removed. 
                //"dom": "<'row'<'col-md-8 col-sm-12'pli><'col-md-4 col-sm-12'<'table-group-actions pull-right'>>r>t<'row'<'col-md-8 col-sm-12'pli><'col-md-4 col-sm-12'>>",
                "bStateSave": false, // save datatable state(pagination, sort, etc) in cookie.
                "lengthMenu": [
                    [10, 20, 50, 100],
                    [10, 20, 50, 100] // change per page values here
                ],
                "pageLength": 100, // default record count per page
                //Code for sorting
                "deferRender": true,
                "stateSave": true,
                "columns": [{
                        "data": 0,
                        "bSortable": false
                    },{
                        "data": 1,
                        "bSortable": false
                    }, {
                        "data": 2,
                        "name": 'varTitle',
                        className: 'text-left Organizations_title_P_2 mob-show_div',
                        "bSortable": false
                    }, {
                        "data": 3,
                        "bSortable": false,
                        className: 'text-center Organizations_designation_P_3'
                    }, {
                        "data": 4,
                        "bSortable": false,
                        className: 'text-center Organizations_porganization_P_4'
                    },
                    {
                        "data": 5,
                        "name": 'intDisplayOrder',
                        className: 'text-center Organizations_displayorder_P_5'
                    },
                    {
                        "data": 6,
                        "bSortable": false,
                        className: 'text-center publish_switch Organizations_publish_P_6'
                    },
                    {
                        "data": 7,
                        "bSortable": false,
                        className: 'text-right Organizations_dactions_P_7 last_td_action mob-show_div'
                    },
                    {
                        "data": 8,
                        className: 'text-center'
                    }],
                "columnDefs": [{
                        "targets": [1,8],
                        "visible": false,
                        "searchable": false
                    }],
                "ajax": {
                    "url": window.site_url + "/powerpanel/organizations/get_list", // ajax source
                },
                'fnCreatedRow': function (nRow, aData, iDataIndex) {
                    $(nRow).attr('data-order', aData[8]);
                },
                "order": [
                    [5, "asc"]
                ]
            }
        });
        $('#datatable_ajax tbody').on('click', '.moveDwn', function () {
            var order = $(this).data('order');
            var exOrder = order + 1;
            var parentRecordId = $(this).attr('data-parentRecordId');
            reorder(order, exOrder, parentRecordId);
        });
        $('#datatable_ajax tbody').on('click', '.moveUp', function () {
            var order = $(this).data('order');
            var exOrder = order - 1;
            exOrder = (exOrder == 0) ? 1 : exOrder;
            var parentRecordId = $(this).attr('data-parentRecordId');
            reorder(order, exOrder, parentRecordId);
        });
        $(document).on('change', '#statusfilter', function (e) {
            e.preventDefault();
            var action = $('#statusfilter').val();
            if (action != " ") {
                //$.cookie('ServiceCategoryStatus',action);
            } else {
                //$.removeCookie('ServiceCategoryStatus');
            }
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
        $(document).on("switchChange.bootstrapSwitch", ".publish", function (event, state) {
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
                success: function (data) {
                    grid.getDataTable().ajax.reload(null, false);
                },
                error: function () {
                    console.log('error!');
                }
            });
        });
        $(document).on('keyup', '#searchfilter', function (e) {
            e.preventDefault();
            var action = $('#searchfilter').val();
            if (action.length >= 2) {
                $.cookie('ServiceCategorySearch', action);
                grid.setAjaxParam("customActionType", "group_action");
                grid.setAjaxParam("searchValue", action);
                grid.setAjaxParam("id", grid.getSelectedRows());
                grid.getDataTable().ajax.reload();
            } else if (action.length < 1) {
                $.removeCookie('ServiceCategorySearch');
                grid.setAjaxParam("customActionType", "group_action");
                grid.setAjaxParam("searchValue", action);
                grid.setAjaxParam("id", grid.getSelectedRows());
                grid.getDataTable().ajax.reload();
            }
        });
        if (!showChecker) {
            grid.getDataTable().column(0).visible(false);
            grid.getDataTable().column(1).visible(false);
            grid.getDataTable().column(5).visible(false);
            grid.getDataTable().column(6).visible(false);
        } else {
             grid.getDataTable().column(0).visible(true);
            grid.getDataTable().column(1).visible(false);
            grid.getDataTable().column(5).visible(true);
            grid.getDataTable().column(6).visible(true);
        }
       
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
var grid1Rows = 0;
var grid1 = "";
var ApprovedTableDatatablesAjax = function () {
    var handleRecords1 = function () {
        var action = $('#category_id').val();
        grid1 = new Datatable();
        grid1.setAjaxParam("catValue", action);
        grid1.init({
            src: $("#datatable_ajax_approved"),
            onSuccess: function (grid1, response) {
                grid1Rows = response.recordsTotal;
                if (response.recordsTotal < 1) {
                    $('.deleteMass').hide();
                } else {
                    $('.deleteMass').show();
                }
                if (response.newRecordCount > 0) {
                    $('.newcounter').text(response.newRecordCount).show();
                } else {
                    $('.newcounter').hide();
                }
                setTimeout(function(){
                    $.each(settingarray, function (index, value) {
                          if (index == 'A') {
                              $.each(value, function (index, columnid) {
                                  $('#datatable_ajax_approved thead').find('.'+columnid).addClass("hidecolumn");
                                  $("#datatable_ajax_approved tbody").find('tr').each(function (index, value) {
                                      $(this).find('.'+columnid).addClass("hidecolumn");
                                  });
                              });
                          }
                      });   
                }, 1500);
                // grid1:        grid1 object
                // response:    json object of server side ajax response
                // execute some code after table records loaded
            },
            onError: function (grid1) {
                // execute some code on network or other general error  
            },
            onDataLoad: function (grid1) {
                // execute some code on ajax data load
                if ($('.pagination-panel .prev').hasClass('disabled')) {
                    $("#datatable_ajax_approved tbody tr:first").find('.moveUp').hide();
                }
                if ($('.pagination-panel .next').hasClass('disabled')) {
                    $("#datatable_ajax_approved tbody tr:last").find('.moveDwn').hide();
                }
                $('.make-switch').bootstrapSwitch();
            },
            loadingMessage: 'Loading...',
            dataTable: {// here you can define a typical datatable settings from http://datatables.net/usage/options 
                // Uncomment below line("dom" parameter) to fix the dropdown overflow issue in the datatable cells. The default datatable layout
                // setup uses scrollable div(table-scrollable) with overflow:auto to enable vertical scroll(see: assets/global/scripts/datatable.js). 
                // So when dropdowns used the scrollable div should be removed. 
                //"dom": "<'row'<'col-md-8 col-sm-12'pli><'col-md-4 col-sm-12'<'table-group-actions pull-right'>>r>t<'row'<'col-md-8 col-sm-12'pli><'col-md-4 col-sm-12'>>",
                "bStateSave": false, // save datatable state(pagination, sort, etc) in cookie.
                "lengthMenu": [
                    [10, 20, 50, 100],
                    [10, 20, 50, 100] // change per page values here
                ],
                "pageLength": 50, // default record count per page
                //Code for sorting
                "deferRender": true,
                "stateSave": true,
                "columns": [{
                        "data": 0,
                        "bSortable": false
                    },{
                        "data": 1,
                        "name": 'varTitle',
                        className: 'text-left Organizations_title_P_2 mob-show_div',
                        "bSortable": true
                    }, {
                        "data": 2,
                        "bSortable": false,
                        className: 'text-center Organizations_designation_P_3'
                    }, {
                        "data": 3,
                        "bSortable": false,
                        className: 'text-center Organizations_porganization_P_4'
                    },
                    {
                        "data": 4,
                        "bSortable": false,
                        className: 'text-right Organizations_dactions_P_7 last_td_action mob-show_div'
                    }],
                "columnDefs": [{
                        "targets": [0],
                        "visible": false,
                        "searchable": false
                    }],
                "ajax": {
                    "url": window.site_url + "/powerpanel/organizations/get_list_New", // ajax source
                },
                "order": [
                    [1, "asc"]
                ]
            }
        });
        $(document).on('change', '#statusfilter', function (e) {
            e.preventDefault();
            var action = $('#statusfilter').val();
            if (action != " ") {
                //$.cookie('ServiceCategoryStatus',action);
            } else {
                //$.removeCookie('ServiceCategoryStatus');
            }
            if (action != "") {
                grid1.setAjaxParam("customActionType", "group_action");
                grid1.setAjaxParam("statusValue", action);
                grid1.setAjaxParam("id", grid1.getSelectedRows());
                grid1.getDataTable().ajax.reload();
            } else {
                grid1.setAjaxParam("customActionType", "group_action");
                grid1.setAjaxParam("statusValue", action);
                grid1.setAjaxParam("id", grid1.getSelectedRows());
            }
        });
        $(document).on("switchChange.bootstrapSwitch", ".publish", function (event, state) {
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
                success: function (data) {
                    grid1.getDataTable().ajax.reload(null, false);
                },
                error: function () {
                    console.log('error!');
                }
            });
        });
        $('a[data-toggle="tab"][id="MenuItem1"]').on('shown.bs.tab', function (e) {
            e.preventDefault();
            grid.setAjaxParam("id", grid.getSelectedRows());
            grid.getDataTable().ajax.reload();
        });
        $('a[data-toggle="tab"][id="MenuItem2"]').on('shown.bs.tab', function (e) {
            e.preventDefault();
            grid1.setAjaxParam("id", grid1.getSelectedRows());
            grid1.getDataTable().ajax.reload();
        });
        $(document).on('keyup', '#searchfilter', function (e) {
            e.preventDefault();
            var action = $('#searchfilter').val();
            if (action.length >= 2) {
                $.cookie('ServiceCategorySearch', action);
                grid1.setAjaxParam("customActionType", "group_action");
                grid1.setAjaxParam("searchValue", action);
                grid1.setAjaxParam("id", grid1.getSelectedRows());
                grid1.getDataTable().ajax.reload();
            } else if (action.length < 1) {
                $.removeCookie('ServiceCategorySearch');
                grid1.setAjaxParam("customActionType", "group_action");
                grid1.setAjaxParam("searchValue", action);
                grid1.setAjaxParam("id", grid1.getSelectedRows());
                grid1.getDataTable().ajax.reload();
            }
        });
        grid1.setAjaxParam("customActionType", "group_action");
        grid1.clearAjaxParams();
        grid1.getDataTable().columns().iterator('column', function (ctx, idx) {
            $(grid1.getDataTable().column(idx).header()).append('<span class="sort-icon"/>');
        });
    }
    return {
        //main function to initiate the module
        init: function () {
            handleRecords1();
        }
    };
}();
$(window).on('load', function () {
    if ($.cookie('ServiceCategorySearch')) {
        $('#searchfilter').val($.cookie('ServiceCategorySearch'));
        $('#searchfilter').trigger('keyup');
    }
});
jQuery(document).ready(function () {
    $.ajaxSetup({
        headers: {
            'X-CSRF-Token': $('input[name="_token"]').val()
        }
    });
    TableDatatablesAjax.init();
    //ApprovedTableDatatablesAjax.init();
});
$('a[data-toggle="tab"][id="MenuItem2"]').on('shown.bs.tab', function (e) {
    if (!$.fn.DataTable.isDataTable('#datatable_ajax_approved')) {
        ApprovedTableDatatablesAjax.init();
    }
});
function reorder(curOrder, excOrder, parentRecordId) {
    var ajaxurl = site_url + '/powerpanel/organizations/reorder';
    $.ajax({
        url: ajaxurl,
        data: {
            order: curOrder,
            exOrder: excOrder,
            parentRecordId: parentRecordId
        },
        type: "POST",
        dataType: "HTML",
        success: function (data) {
        },
        complete: function () {
            grid.getDataTable().ajax.reload(null, false);
        },
        error: function () {
            console.log('error!');
        }
    });
}


function KeycheckOnlyPhonenumber(e) {
    var t = 0;
    t = document.all ? 3 : document.getElementById ? 1 : document.layers ? 2 : 0;
    if (document.all)
        e = window.event;
    var n = "";
    var r = "";
    if (t == 2) {
        if (e.which > 0)
            n = "(" + String.fromCharCode(e.which) + ")";
        r = e.which
    } else {
        if (t == 3) {
            r = window.event ? event.keyCode : e.which
        } else {
            if (e.charCode > 0)
                n = "(" + String.fromCharCode(e.charCode) + ")";
            r = e.charCode
        }
    }
    if (r >= 65 && r <= 90 || r >= 97 && r <= 122 || r >= 33 && r <= 39 || r >= 42 && r <= 42 || r >= 44 && r <= 44 || r >= 46 && r <= 47 || r >= 58 && r <= 64 || r >= 91 && r <= 96 || r >= 123 && r <= 126) {
        return false
    }
    return true
}
$.validator.addMethod("emailFormat", function (value, element) {
    // allow any non-whitespace characters as the host part
    return this.optional(element) || /^[_A-Za-z0-9-]+(\.[_A-Za-z0-9-]+)*@[A-Za-z0-9-]+(\.[A-Za-z0-9-]+)*(\.[A-Za-z]{2,4})$/.test(value);
}, 'Enter valid email format');
$.validator.addMethod("xssProtection", function (value, element) {
    // allow any non-whitespace characters as the host part
    return this.optional(element) || /<(\w+)((?:\s+\w+(?:\s*=\s*(?:(?:"[^"]*")|(?:'[^']*')|[^>\s]+))?)*)\s*(\/?)>/.test(value) == false ? true : false;
}, 'Enter valid input');
$.validator.addMethod("check_special_char", function (value, element) {
    if (value != '') {
        if (value.match(/^[\x20-\x7E\n]+$/)) {
            return true;
        } else {
            return false;
        }
    } else {
        return true;
    }
}, 'Please enter valid input');
$.validator.addMethod('no_url', function (value, element) {
    var re = /^[a-zA-Z0-9\-\.\:\\]+\.(com|org|net|mil|edu|COM|ORG|NET|MIL|EDU)$/;
    var re1 = /(ftp|http|https):\/\/(\w+:{0,1}\w*@)?(\S+)(:[0-9]+)?(\/|\/([\w#!:.?+=&%@!\-\/]))?/;
    var trimmed = $.trim(value);
    if (trimmed == '') {
        return true;
    }
    if (trimmed.match(re) == null && re1.test(trimmed) == false) {
        return true;
    }
}, "URL doesn't allowed");

$.validator.addMethod('validUrl', function (value, element) {
    var url = $.validator.methods.url.bind(this);
    return url(value, element) || url('http://' + value, element);
}, 'Please enter a valid URL');

$.validator.addMethod("phonenumber", function (value, element) {
    var numberPattern = /\d+/g;
    var newVal = value.replace(/\D/g);
    if (parseInt(newVal) <= 0) {
        return false;
    } else {
        return true;
    }
}, 'Please enter a valid phone number.');
$.validator.addMethod("noSpace", function (value, element) {
    if (value.trim().length <= 0) {
        return false;
    } else {
        return true;
    }
}, "No space please don't leave it empty");

$.validator.addMethod("alphanumeric", function (value, element) {
    return this.optional(element) || /^[\w.]+$/i.test(value);
}, "Letters, numbers, and underscores only please");

$.validator.addMethod("lettersonly", function (value, element) {
    return this.optional(element) || /^[a-z]+$/i.test(value);
}, "Letters only please");

$("#varPhoneNo").bind("paste", function (e) {
    // access the clipboard using the api
    return false;
});
var blacklist = /\b(nude|naked|sex|porn|porno|sperm|penis|pussy|vegina|boobs|asshole|bitch|dick)\b/;
jQuery.validator.addMethod("badwordcheck", function (value) {
    return !blacklist.test(value.toLowerCase());
}, "Please remove bad word/inappropriate language.");

$.validator.addMethod("languageTest", function (value) {
    regEx = /^[a-zA-Z0-9\-\+\"\(\)\'\:\$\%\<\>\@\!\#\&\*\,\=\{\}\.\/\;\[\]\^\_\s]+$/;
    if (value != '') {
        if (!regEx.test(value))
            return false;
        else if (regEx.test(value))
            return true;
    } else {
        return true;
    }
}, "Please enter valid input.");

$.validator.addMethod("emailFormat", function (value, element) {
    // allow any non-whitespace characters as the host part
    return this.optional(element) || /^[_A-Za-z0-9-]+(\.[_A-Za-z0-9-]+)*@[A-Za-z0-9-]+(\.[A-Za-z0-9-]+)*(\.[A-Za-z]{2,4})$/.test(value);
}, 'Enter valid email format.');

$.validator.addMethod("phonenumber_mobile", function (value, element) {
    return (value.match(/^[0-9-_ +()]+$/i));
}, 'Please enter valid phone number.');

$.validator.addMethod("phonenumber", function (value, element) {
    var numberPattern = /\d+/g;
    var newVal = value.replace(/\D/g);
    if (parseInt(newVal) <= 0) {
        return false;
    } else {
        return true;
    }
}, 'Please enter a valid phone number.');