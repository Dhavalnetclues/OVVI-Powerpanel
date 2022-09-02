var pageDelete = 'Are you sure you want to permanently delete this data? You will not able to retrieve it. Press DELETE to confirm.';
// var DELETE_ATLEAST_ONE = Lang.get('template.oneRecordDeleted');
// var BLOCK_ATLEAST_ONE = Lang.get('Please select at-least one record to block.');
// var DELETE_CONFIRM_MESSAGE = (window.location.href.indexOf("page") > -1) ? pageDelete : Lang.get('template.selectedDeleted');

var DELETE_ATLEAST_ONE = 'Please select at-least one record to delete.';
var BLOCK_ATLEAST_ONE = 'Please select at-least one record to block.';
var DELETE_CONFIRM_MESSAGE = pageDelete;
var BLOCK_CONFIRM_MESSAGE = 'Caution! The selected records will be block. Press BLOCK to confirm.';
var alias = '';

var blockedAlerts = false;
if (window.location.href.indexOf("blocked-ips") > 0) {
    blockedAlerts = true;
}

if (blockedAlerts) {
    pageDelete = 'Caution! The records with selected IPs will be Unblocked. Press UNBLOCK to confirm.';
    DELETE_ATLEAST_ONE = 'Please select at-least one record to unblock';
    DELETE_CONFIRM_MESSAGE = pageDelete;
}

$(document).ready(function() {
    var tab = "";
    $(document).on('click', '.delete', function(e) {
        e.preventDefault();
        var controller = $(this).data('controller');
        alias = $(this).data('alias');
        tab = $(this).data('tab');
        deleteItemModal();
        $('#confirm .delMsg').text(DELETE_CONFIRM_MESSAGE);
        $('#delete').show();
    });


    $(document).on('click', '#delete', function() {
        var value = $(this).val();
        deleteItem(DELETE_URL, alias, tab);
    });

    $(document).on('click', '#deleteAll', function() {
        var value = $(this).val();
        Delete_Records(value);
    });
    $(document).on('click', '#deleteAllBlock', function() {
        var value = $(this).val();
        Block_Records(value);
    });

    $(document).on('click', '.deleteMass', function(e) {
        var value = $(this).val();
        e.preventDefault();
        var CheckedLength = $(".chkDelete:checked").length;
        if (CheckedLength == 0) {
            $('#confirmForAll .delMsg').text(DELETE_ATLEAST_ONE);
            $('#deleteAll').hide();
        }
        if (CheckedLength > 0) {
            $('#confirmForAll .delMsg').text(DELETE_CONFIRM_MESSAGE);
            $("#deleteAll").attr('value', value);
            $('#deleteAll').show();
        }
        deleteMultiple();
    });
    $(document).on('click', '.blockMass', function(e) {
        var value = $(this).val();
        e.preventDefault();
        var CheckedLength = $("input[type='checkbox'][class='blkDelete']:checked").length;
        if (CheckedLength == 0) {
            $('#confirmForAllBlock .delMsg').text(BLOCK_ATLEAST_ONE);
            $('#deleteAll').hide();
        }
        if (CheckedLength > 0) {
            $('#confirmForAllBlock .delMsg').text(BLOCK_CONFIRM_MESSAGE);
            $("#deleteAllBlock").attr('value', value);
            $('#deleteAllBlock').show();
        }
        blockMultiple();
    });
});

$(document).on("click", ".group-checkable", function() {
    //$('.group-checkable').click(function () {
    var parentTableObj = $(this).parents('table');
    if ($(this).parent().attr('class') != 'checked') {
        $(parentTableObj).find('.chkDelete').each(function() {
            $(this).prop('checked', true);
            $(this).parent().addClass('checked');
        });
    } else {
        $(parentTableObj).find('.chkDelete').each(function() {
            $(this).prop('checked', false);
            $(this).parent().removeClass('checked');
        });
    }
});


// Delete button hide show of listing page
// --------------------------------------------------- Start

$(document).on("change", "#datatable_ajax_wrapper .multiSelectList", function() {
    var CheckedLength = $("#datatable_ajax_wrapper .multiSelectList:checked").length;

    if(CheckedLength == 0) {
        $('.deleteMass').addClass("hide-btn-mob");
    }else if(CheckedLength > 0) {
        $('.deleteMass').removeClass("hide-btn-mob");
    }
});

$(document).on("click", ".nav-item", function() {
    $('.deleteMass').addClass("hide-btn-mob");
    $('.multiSelectList').prop('checked', false);
});

// --------------------------------------------------- End
// Delete button hide show of listing page


// Search Filter reset on click close sign   --   Listing Page
// ------------------------------------------------------------------ Start
$('.iconsearch').on('click',function() {
    $('.filter-search').toggleClass('visible');
    $("#searchfilter").val('').keyup();
    if($("#searchfilter").val() == '' && !$('.filter-search').hasClass('visible')){
    	 $('.card').loader(loaderConfig);
    	 setTimeout(function(){ if($('.table:visible').find('tbody tr').length > 0){ $.loader.close(true);} },2000);
    }
});


// ------------------------------------------------------------------ End
// Search Filter reset on click close sign   --   Listing Page


function deleteItem(ajaxUrl, slug, tab) {
    $.ajax({
        url: ajaxUrl,
        data: {
            ids: [slug],
            "value": tab
        },
        type: "POST",
        dataType: "HTML",
        success: function(data) {
            $('#confirm').modal('hide');
            var x = location.href;
            window.location.href = x + "?tab=" + tab;
            //            if (grid != '' && typeof grid != "undefined" && grid != "") {
            //                grid.getDataTable().ajax.reload(GridCallback, false);
            //            }
            //
            //            if (grid1 != '' && typeof grid1 != "undefined") {
            //                grid1.getDataTable().ajax.reload(GridCallback, false);
            //            }

        },
        complete: function() {},
        error: function() {
            console.log('error!');
        },
        async: false
    });
}

function Delete_Records(value) {
    var matches = [];
    var ref_this = $(".portlet-body .table-container ul.nav-tabs li.active a").attr('href');
    if (typeof ref_this != 'undefined') {
        $(ref_this).find("table .chkDelete:checked").each(function() {
            matches.push(this.value);
        });
    } else {
        $(".chkDelete:checked").each(function() {
            matches.push(this.value);
        });
    }

    jQuery.ajax({
        type: "POST",
        url: DELETE_URL,
        data: {
            "ids": matches,
            "value": value
        },
        async: false,
        success: function(result) {
            $('#confirmForAll').modal('hide');
            var x = location.href;
            window.location.href = x + "?tab=" + value;
            //            location.reload();
            //            if (grid != '' && typeof grid != "undefined" && grid != "") {
            //                grid.getDataTable().ajax.reload(GridCallback, false);
            //            }
            //
            //            if (grid1 != '' && typeof grid1 != "undefined") {
            //                grid1.getDataTable().ajax.reload(GridCallback, false);
            //            }
        },
        complete: function() {}
    });
}

function Block_Records(value) {
    var matches = [];
    var ref_this = $(".portlet-body .table-container ul.nav-tabs li.active a").attr('href');
    if (typeof ref_this != 'undefined') {
        $(ref_this).find("table .blkDelete:checked").each(function() {
            matches.push(this.value);
        });
    } else {
        $(".blkDelete:checked").each(function() {
            matches.push(this.value);
        });
    }

    jQuery.ajax({
        type: "POST",
        url: BLOCK_Ml_URL,
        data: {
            "ids": matches,
            "value": value
        },
        async: false,
        success: function(result) {
            $('#confirmForAllBlock').modal('hide');
            grid.getDataTable().ajax.reload(null, false);
            grid1.getDataTable().ajax.reload(null, false);
            //            location.reload();
            //            if (grid != '' && typeof grid != "undefined" && grid != "") {
            //                grid.getDataTable().ajax.reload(GridCallback, false);
            //            }
            //
            //            if (grid1 != '' && typeof grid1 != "undefined") {
            //                grid1.getDataTable().ajax.reload(GridCallback, false);
            //            }
        },
        complete: function() {}
    });
}

function GridCallback(Grid) {
    if ($('.dataTable tbody tr').length <= 1) {
        location.reload(true);
    }
}

function deleteMultiple() {
    // $('#confirmForAll').modal({
    //     backdrop: 'static',
    //     keyboard: false
    // });
    $('#confirmForAll').modal('show');
}

function blockMultiple() {
    // $('#confirmForAllBlock').modal({
    //     backdrop: 'static',
    //     keyboard: false
    // });
    $('#confirmForAllBlock').modal('show');
}

function deleteItemModal() {
    // $('#confirm').modal({
    //     backdrop: 'static',
    //     keyboard: false
    // });
    $('#confirm').modal('show');
}

/*$(document).on("contextmenu", ".moveTo", function(event) {
 event.preventDefault();
 var curOrder = $(this).data('order');
 var module = $(this).data('module');
 $('#moveTo input[name=order]').val(curOrder);
 $('#moveTo input[name=exorder]').val(curOrder);
 $('#moveTo #go').data('module',module);
 moveToModal();
 });*/

function moveToModal() {
    // $('#moveTo').modal({
    //     backdrop: 'static',
    //     keyboard: false
    // });
    $('#moveTo').modal('show');
}

//$('input[name="display_order"]').keypress(function (event) {
//		return isNumber(event, this);
//});

function isNumber(evt, element) {

    var charCode = (evt.which) ? evt.which : event.keyCode;
    if (
        (charCode < 48 || charCode > 57)) // allow only digit.
        return false;

    return true;
}

$(document).on('click', '#go', function() {
    jQuery.ajax({
        type: "POST",
        url: $(this).data('module') + '/swaporder',
        data: {
            "order": $('#moveTo input[name=order]').val(),
            "exOrder": $('#moveTo input[name=exorder]').val()
        },
        async: false,
        success: function(result) {
            $('#moveTo').modal('hide');
        },
        complete: function() {
            grid.getDataTable().ajax.reload(null, false);
            grid1.getDataTable().ajax.reload(null, false);
        }
    });
});


$(document).on('click', '#refresh', function(e) {
    $("#rolefilter, #categoriesfilter, #modulefilter, #foritem, #statusfilter,#sectorfilter,#linkFilterType, #pageFilter, #bannerFilter, #bannerFilterType,#category_id,#searchfilter,.category_filter,.list_head_filter,#category,#photoalbumfilter,#feedbackRange,#start_date,#end_date,#monthFilter,#yearFilter").val('').trigger('change');
    $('#searchfilter').val('').keyup();
});
$(document).on('click', '#seo_edit', function(e) {
    $("#seo_edit_dispaly").show();
    $("#seo_edit_time").show();
    $("#seo_edit").hide();
});
$(document).on('click', '#seo_edit_time', function(e) {
    $("#seo_edit_dispaly").hide();
    $("#seo_edit_time").hide();
    $("#seo_edit").show();
});

$(document).on('click', '#MenuItem4', function(e) {
    $("#sectorfilter").val('').trigger('change');
    $('#searchfilter').keyup();
});

$(document).on('click', '#MenuItem3', function(e) {
    $("#sectorfilter").val('').trigger('change');
    $('#searchfilter').keyup();
});

$(document).on('click', '#MenuItem2', function(e) {
    $("#sectorfilter").val('').trigger('change');
    $('#searchfilter').keyup();
});

$(document).on('click', '#MenuItem1', function(e) {
    $("#sectorfilter").val('').trigger('change');
    $('#searchfilter').keyup();
});

$(document).on('click', '#MenuItem5', function(e) {
    $("#sectorfilter").val('').trigger('change');
    $('#searchfilter').keyup();
});

$('.fancybox-buttons').fancybox({
    autoWidth: true,
    autoHeight: true,
    autoResize: true,
    autoCenter: true,
    closeBtn: true,
    openEffect: 'elastic',
    closeEffect: 'elastic',
    helpers: {
        title: {
            type: 'inside',
            position: 'top'
        }
    },
    beforeShow: function () {
        this.title = $(this.element).data("title");
    }
});
$(".fancybox-thumb").fancybox({
    prevEffect: 'none',
    nextEffect: 'none',
    helpers: {
        title: {
            type: 'outside'
        },
        thumbs: {
            width: 60,
            height: 50
        }
    }
});