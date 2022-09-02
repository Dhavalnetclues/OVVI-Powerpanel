function expandcollapsepanel(object, panelid, elementid, id, tab = "primary-") {

var panelid = tab + 'tasklisting' + id;
        var elementid = tab + 'mainsingnimg' + id;
        var id = id;
        var attr = $('#' + elementid).attr('class');
        if (attr == 'ri-add-box-line') {
        $('.dataTable .la-minus-square').addClass('la-plus-square').removeClass('la-minus-square');
        $('.dataTable .la-minus-circle').addClass('la-history').removeClass('la-minus-circle');
        $('.dataTable .multitasker').hide();
        $.ajax({
        type: "POST",
                url: getChildData,
                data: "panelid=" + panelid + "&elementid=" + elementid + "&id=" + id,
                async: false,
                success: function(data) {
                var itemID = tab + 'tasklisting' + id;
                        if ($('#' + itemID).length == 0) {
                var treq = $(object).closest('tr');
                        $(treq).after('<tr id="' + tab + 'tasklisting' + id + '" class="odd multitasker" role="row"><td align="left" colspan="30" class=""><div id="' + tab + 'ChildDiv' + id + '"></div></td></tr>');
                }
                $("#" + itemID).show();
                        $("#" + tab + "ChildDiv" + id).html(data);
                        $('#' + elementid).removeClass('la-plus-square');
                        $("#" + elementid).addClass("la la-minus-square");
                        var elementid_rolback = tab + 'mainsingnimg_rollback' + id;
                        var attr = $('#' + elementid_rolback).attr('class');
                        var itemID = tab + 'tasklisting_rollback' + id;
                        $('#' + elementid_rolback).removeClass(attr);
                        $("#" + elementid_rolback).addClass("ri-history-line");
                        $("#" + itemID).hide();
                }
        });
} else {
var elementid = tab + 'mainsingnimg' + id;
        var attr = $('#' + elementid).attr('class');
        var itemID = tab + 'tasklisting' + id;
        $("#" + tab + 'ChildDiv' + id).html('');
        $('#' + elementid).removeClass(attr);
        $("#" + elementid).addClass("ri-add-box-line");
        $("#" + itemID).hide();
}


}

function expandcollapsepanel_rolback(object, panelid, elementid_rolback, id, tab = "primary-") {
var panelid = tab + 'tasklisting_rollback' + id;
        var elementid_rolback = tab + 'mainsingnimg_rollback' + id;
        var id = id;
        var attr_rolback = $('#' + elementid_rolback).attr('class');
        if (attr_rolback == 'ri-history-line') {
$('.dataTable .la-minus-circle').addClass('la-history').removeClass('la-minus-circle');
        $('.dataTable .la-minus-square').addClass('la-plus-square').removeClass('la-minus-square');
        $('.dataTable .multitasker').hide();
        $.ajax({
        type: "POST",
                url: getChildData_rollback,
                data: "panelid=" + panelid + "&elementid_rolback=" + elementid_rolback + "&id=" + id,
                async: false,
                success: function(data) {
                var itemID = tab + 'tasklisting_rollback' + id;
                        if ($('#' + itemID).length == 0) {
                var treq = $(object).closest('tr');
                        $(treq).after('<tr id="' + tab + 'tasklisting_rollback' + id + '" class="odd multitasker" role="row"><td align="left" colspan="30" class=""><div id="' + tab + 'ChildDiv_rollback' + id + '"></div></td></tr>');
                }
                $("#" + itemID).show();
                        $("#" + tab + 'ChildDiv_rollback' + id).html(data);
                        $('#' + elementid_rolback).removeClass(attr_rolback);
                        $("#" + elementid_rolback).addClass("la la-minus-circle");
                        var elementid = tab + 'mainsingnimg' + id;
                        var attr = $('#' + elementid).attr('class');
                        var itemID = tab + 'tasklisting' + id;
                        $("#" + tab + 'ChildDiv' + id).html('');
                        $('#' + elementid).removeClass(attr);
                        $("#" + elementid).addClass("ri-add-box-line");
                        $("#" + itemID).hide();
                }
        });
} else {

var elementid_rolback = tab + 'mainsingnimg_rollback' + id;
        var attr_rolback = $('#' + elementid_rolback).attr('class');
        var itemID = tab + 'tasklisting_rollback' + id;
        $("#" + tab + 'ChildDiv_rollback' + id).html('');
        $('#' + elementid_rolback).removeClass(attr_rolback);
        $("#" + elementid_rolback).addClass("ri-history-line");
        $("#" + itemID).hide();
}


}

function update_mainrecord(id, main_id, PUserid,flag) {
$("#AlertNo").attr('value', 'A');
        $('#Approve .approveMsg').text("Are you sure you want to update to main record? Press OK to confirm.");
        $('#Approve1').show();
        $('#Approve').modal({
backdrop: 'static',
        keyboard: false
});
        $(document).on('click', '#Approve1', function() {
ApproveRecord(id, main_id, PUserid,flag);
});
        }

function ApproveRecord(id, main_id, PUserid,flag) {
$.ajax({
type: "POST",
        url: ApprovedData_Listing,
        data: {
        'id': id,
                'main_id': main_id,
                'PUserid': PUserid,
                'flag':flag
        },
        async: false,
        success: function(data) {
        $('#Approved .approveMsg').text("The record has been approved and successfully published on the website.");
                $('#Approved').show();
                $('#Approved').modal({
        backdrop: 'static',
                keyboard: false
        });
                $(document).on('click', '#ApprovedSuccess', function() {
        location.reload();
        });
        }
});
        }

function loadModelpopup(id, UserID, namespace, fkMainRecord) {
$.ajax({
type: "POST",
        url: Get_Comments,
        data: {
        'id': id,
                'UserID': UserID,
                'namespace': namespace,
                'fkMainRecord': fkMainRecord
        },
        async: false,
        success: function(data) {
        document.getElementById('test').innerHTML = data;
        }
});
        $('#CmsPageComments1').show();
        $('#CmsPageComments1').modal({
backdrop: 'static',
        keyboard: false
});
        document.getElementById('id').value = id;
        document.getElementById('CmsPageComments').value = '';
        document.getElementById('UserID').value = UserID;
        document.getElementById('fkMainRecord').value = fkMainRecord;
        }


$(document).ready(function() {
var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
  return new bootstrap.Tooltip(tooltipTriggerEl)
})
        setInterval(function() {
        $('.addhiglight').closest("td").closest("tr").addClass('higlight');
        }, 800);
        if (!showChecker) {
if (grid != ""){
grid.getDataTable().column(0).visible(false);
}
if (grid1 != ""){
grid1.getDataTable().column(0).visible(false);
}

// setInterval(function() {
//     $('.checker').closest("td").hide();
//     $('.checker').closest("th").hide();            
// }, 800);
}
});