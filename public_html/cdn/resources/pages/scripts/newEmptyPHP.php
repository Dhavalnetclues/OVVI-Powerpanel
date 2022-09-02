function expandcollapsepanel(object, panelid, elementid, id) {

    var activeTabcontentId = $('.tab-content').find('.tab-pane.active').attr('id');
    var panelid = 'tasklisting' + id;
    var elementid = 'mainsingnimg' + id;
    var id = id;
    var attr = $('#' + activeTabcontentId + ' ' + '#' + elementid).attr('class');

    if (attr == 'ri-add-box-line') {
        $('#' + activeTabcontentId + ' ' + '.dataTable .la-minus-square').addClass('la-plus-square').removeClass('la-minus-square');
        $('#' + activeTabcontentId + ' ' + '.dataTable .fa-minus-circle').addClass('fa-history').removeClass('fa-minus-circle');
        $('#' + activeTabcontentId + ' ' + '.dataTable .multitasker').hide();
        $.ajax({
            type: "POST",
            url: getChildData,
            data: "panelid=" + panelid + "&elementid=" + elementid + "&id=" + id,
            async: false,
            success: function (data) {
                var itemID = 'tasklisting' + id;
                if ($('#' + activeTabcontentId + ' ' + '#' + itemID).length == 0) {
                    var treq = $(object).closest('tr');
                    $(treq).after('<tr id="tasklisting' + id + '" class="odd multitasker" role="row"><td align="left" colspan="30" class=""><div id="ChildDiv' + id + '"></div></td></tr>');
                }
                $('#' + activeTabcontentId + ' ' + "#" + itemID).show();
                $('#' + activeTabcontentId + ' ' + "#ChildDiv" + id).html(data);
                $('#' + activeTabcontentId + ' ' + '#' + elementid).removeClass('la-plus-square');
                $('#' + activeTabcontentId + ' ' + "#" + elementid).addClass("la la-minus-square");

                var elementid_rolback = 'mainsingnimg_rollback' + id;
                var attr = $('#' + activeTabcontentId + ' ' + '#' + elementid_rolback).attr('class');
                var itemID = 'tasklisting_rollback' + id;

                $('#' + activeTabcontentId + ' ' + '#' + elementid_rolback).removeClass(attr);
                $('#' + activeTabcontentId + ' ' + "#" + elementid_rolback).addClass("ri-history-line");
                $('#' + activeTabcontentId + ' ' + "#" + itemID).hide();
            }
        });
    } else {
        var elementid = 'mainsingnimg' + id;
        var attr = $('#' + activeTabcontentId + ' ' + '#' + elementid).attr('class');
        var itemID = 'tasklisting' + id;
        $('#' + activeTabcontentId + ' ' + "#ChildDiv" + id).html('');
        $('#' + activeTabcontentId + ' ' + '#' + elementid).removeClass(attr);
        $('#' + activeTabcontentId + ' ' + "#" + elementid).addClass("ri-add-box-line");
        $('#' + activeTabcontentId + ' ' + "#" + itemID).hide();
    }
}

function expandcollapsepanel_rolback(object, panelid, elementid_rolback, id) {

    var activeTabcontentId = $('.tab-content').find('.tab-pane.active').attr('id');
    var panelid = 'tasklisting_rollback' + id;
    var elementid_rolback = 'mainsingnimg_rollback' + id;
    var id = id;
    var attr_rolback = $('#' + activeTabcontentId + ' ' + '#' + elementid_rolback).attr('class');
    if (attr_rolback == 'ri-history-line') {
        $('#' + activeTabcontentId + ' ' + '.dataTable .la-minus-circle').addClass('la-history').removeClass('la-minus-circle');
        $('#' + activeTabcontentId + ' ' + '.dataTable .la-minus-square').addClass('la-plus-square').removeClass('la-minus-square');
        $('#' + activeTabcontentId + ' ' + '.dataTable .multitasker').hide();

        $.ajax({
            type: "POST",
            url: getChildData_rollback,
            data: "panelid=" + panelid + "&elementid_rolback=" + elementid_rolback + "&id=" + id,
            async: false,
            success: function (data) {
                var itemID = 'tasklisting_rollback' + id;
                if ($('#' + activeTabcontentId + ' ' + '#' + itemID).length == 0) {
                    var treq = $(object).closest('tr');
                    $(treq).after('<tr id="tasklisting_rollback' + id + '" class="odd multitasker" role="row"><td align="left" colspan="30" class=""><div id="ChildDiv_rollback' + id + '"></div></td></tr>');
                }
                $('#' + activeTabcontentId + ' ' + "#" + itemID).show();
                $('#' + activeTabcontentId + ' ' + "#ChildDiv_rollback" + id).html(data);
                $('#' + activeTabcontentId + ' ' + '#' + elementid_rolback).removeClass(attr_rolback);
                $('#' + activeTabcontentId + ' ' + "#" + elementid_rolback).addClass("la la-minus-circle");

                var elementid = 'mainsingnimg' + id;
                var attr = $('#' + activeTabcontentId + ' ' + '#' + elementid).attr('class');
                var itemID = 'tasklisting' + id;

                $('#' + activeTabcontentId + ' ' + "#ChildDiv" + id).html('');
                $('#' + activeTabcontentId + ' ' + '#' + elementid).removeClass(attr);
                $('#' + activeTabcontentId + ' ' + "#" + elementid).addClass("ri-add-box-line");
                $('#' + activeTabcontentId + ' ' + "#" + itemID).hide();
            }
        });

    } else {

        var elementid_rolback = 'mainsingnimg_rollback' + id;
        var attr_rolback = $('#' + activeTabcontentId + ' ' + '#' + elementid_rolback).attr('class');
        var itemID = 'tasklisting_rollback' + id;
        $('#' + activeTabcontentId + ' ' + "#ChildDiv_rollback" + id).html('');
        $('#' + activeTabcontentId + ' ' + '#' + elementid_rolback).removeClass(attr_rolback);
        $('#' + activeTabcontentId + ' ' + "#" + elementid_rolback).addClass("ri-history-line");
        $('#' + activeTabcontentId + ' ' + "#" + itemID).hide();
    }

}

function update_mainrecord(id, main_id, PUserid, flag) {
    $("#AlertNo").attr('value', 'A');
    $('#Approve .approveMsg').text("Are you sure you want to publish this record on the website? Click on Yes to confirm.");
    $('#Approve1').show();
    $('#Approve').modal({
        backdrop: 'static',
        keyboard: false
    });
    $(document).on('click', '#Approve1', function () {
        ApproveRecord(id, main_id, PUserid, flag);
    });
}

function ApproveRecord(id, main_id, PUserid, flag) {
    $.ajax({
        type: "POST",
        url: ApprovedData_Listing,
        data: {
            'id': id,
            'main_id': main_id,
            'PUserid': PUserid,
            'flag': flag
        },
        async: false,
        success: function (data) {
            $('#Approved .approveMsg').text("The record has been approved and successfully published on the website.");
            $('#Approved').show();
            $('#Approved').modal({
                backdrop: 'static',
                keyboard: false
            });
            $(document).on('click', '#ApprovedSuccess', function () {
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
        success: function (data) {
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


$(document).ready(function () {
    var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
  return new bootstrap.Tooltip(tooltipTriggerEl)
})

    setInterval(function () {
        $('.addhiglight').closest("td").closest("tr").addClass('higlight');
    }, 800);

    if (!showChecker) {
        grid.getDataTable().column(0).visible(false);
        /*if(grid1 != ""){
         grid1.getDataTable().column(0).visible(false);	
         }*/

        // setInterval(function() {
        //     $('.checker').closest("td").hide();
        //     $('.checker').closest("th").hide();            
        // }, 800);
    }
});