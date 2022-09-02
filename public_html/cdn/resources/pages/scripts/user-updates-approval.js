function expandcollapsepanel(object, panelid, elementid, id) {
    var activeTabcontentId = $('.tab-content').find('.tab-pane.active').attr('id');
    var panelid = 'tasklisting' + id;
    var elementid = 'mainsingnimg' + id;
    var id = id;
    var attr = $('#' + activeTabcontentId + ' ' + '#' + elementid).hasClass('approval_active');
    if (attr == true) {
        $('#' + activeTabcontentId + ' ' + '.dataTable .approval_inactive').addClass('approval_active').removeClass('approval_inactive');
        $('#' + activeTabcontentId + ' ' + '.dataTable .multitasker').hide();
        $.ajax({
            type: "POST",
            url: getChildData,
            data: "panelid=" + panelid + "&elementid=" + elementid + "&id=" + id,
            async: false,
            success: function(data) {
                var itemID = 'tasklisting' + id;
                if ($('#' + activeTabcontentId + ' ' + '#' + itemID).length == 0) {
                    var treq = $(object).closest('tr');
                    $(treq).after('<tr id="tasklisting' + id + '" class="odd multitasker" role="row"><td align="left" colspan="30" class=""><div id="ChildDiv' + id + '"></div></td></tr>');
                }
                $('#' + activeTabcontentId + ' ' + "#" + itemID).show();
                $('#' + activeTabcontentId + ' ' + "#ChildDiv" + id).html(data);
                $('#' + activeTabcontentId + ' ' + '#' + elementid).removeClass('approval_active');
                $('#' + activeTabcontentId + ' ' + "#" + elementid).addClass("approval_inactive");

                var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
                var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
                    return new bootstrap.Tooltip(tooltipTriggerEl)
                });
                // var elementid_rolback = 'mainsingnimg_rollback' + id;
                // var attr = $('#' + activeTabcontentId + ' ' + '#' + elementid_rolback).attr('class');
                // var itemID = 'tasklisting_rollback' + id;

                // $('#' + activeTabcontentId + ' ' + '#' + elementid_rolback).removeClass(attr);
                // $('#' + activeTabcontentId + ' ' + "#" + elementid_rolback).addClass("rollback_active");
                // $('#' + activeTabcontentId + ' ' + "#" + itemID).hide();
            }
        });

    } else {
        var elementid = 'mainsingnimg' + id;
        var attr = $('#' + activeTabcontentId + ' ' + '#' + elementid).hasClass('approval_inactive');
        var itemID = 'tasklisting' + id;
        $('#' + activeTabcontentId + ' ' + "#ChildDiv" + id).html('');
        $('#' + activeTabcontentId + ' ' + '#' + elementid).removeClass('approval_inactive');
        $('#' + activeTabcontentId + ' ' + "#" + elementid).addClass("approval_active");
        $('#' + activeTabcontentId + ' ' + "#" + itemID).hide();
    }
}

function expandcollapsepanel_rolback(object, panelid, elementid_rolback, id) {
    var activeTabcontentId = $('.tab-content').find('.tab-pane.active').attr('id');
    var panelid = 'tasklisting_rollback' + id;
    var elementid_rolback = 'mainsingnimg_rollback' + id;
    var id = id;
    var attr_rolback = $('#' + activeTabcontentId + ' ' + '#' + elementid_rolback).hasClass('rollback_active');
    if (attr_rolback == true) {
        $('#' + activeTabcontentId + ' ' + '.dataTable .rollback_inactive').addClass('rollback_active').removeClass('rollback_inactive');
        $('#' + activeTabcontentId + ' ' + '.dataTable .multitasker').hide();

        $.ajax({
            type: "POST",
            url: getChildData_rollback,
            data: "panelid=" + panelid + "&elementid_rolback=" + elementid_rolback + "&id=" + id,
            async: false,
            success: function(data) {
                var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
                var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
                    return new bootstrap.Tooltip(tooltipTriggerEl)
                });

                var itemID = 'tasklisting_rollback' + id;
                if ($('#' + activeTabcontentId + ' ' + '#' + itemID).length == 0) {
                    var treq = $(object).closest('tr');
                    $(treq).after('<tr id="tasklisting_rollback' + id + '" class="odd multitasker" role="row"><td align="left" colspan="30" class=""><div id="ChildDiv_rollback' + id + '"></div></td></tr>');
                }
                $('#' + activeTabcontentId + ' ' + "#" + itemID).show();
                $('#' + activeTabcontentId + ' ' + "#ChildDiv_rollback" + id).html(data);
                $('#' + activeTabcontentId + ' ' + '#' + elementid_rolback).removeClass("rollback_active");
                $('#' + activeTabcontentId + ' ' + "#" + elementid_rolback).addClass("rollback_inactive");

                var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
                var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
                    return new bootstrap.Tooltip(tooltipTriggerEl)
                });
                // var elementid = 'mainsingnimg' + id;
                // var attr = $('#' + activeTabcontentId + ' ' + '#' + elementid).attr('class');
                // var itemID = 'tasklisting' + id;

                // $('#' + activeTabcontentId + ' ' + "#ChildDiv" + id).html('');
                // $('#' + activeTabcontentId + ' ' + '#' + elementid).removeClass(attr);
                // $('#' + activeTabcontentId + ' ' + "#" + elementid).addClass("approval_active");
                // $('#' + activeTabcontentId + ' ' + "#" + itemID).hide();
            }
        });

    } else {
        var elementid_rolback = 'mainsingnimg_rollback' + id;
        var attr_rolback = $('#' + activeTabcontentId + ' ' + '#' + elementid_rolback).hasClass('rollback_inactive');
        var itemID = 'tasklisting_rollback' + id;
        $('#' + activeTabcontentId + ' ' + "#ChildDiv_rollback" + id).html('');
        $('#' + activeTabcontentId + ' ' + '#' + elementid_rolback).removeClass("rollback_inactive");
        $('#' + activeTabcontentId + ' ' + "#" + elementid_rolback).addClass("rollback_active");
        $('#' + activeTabcontentId + ' ' + "#" + itemID).hide();
    }

}

function update_mainrecord(id, main_id, PUserid, flag) {
    $('#Approve .approveMsg').text("Are you sure you want to publish this record on the website? Click on Yes to confirm.");
    $('#Approve1').show();
    // $('#Approve').modal({
    //     backdrop: 'static',
    //     keyboard: false
    // });
    $('#Approve').modal('show');
    $(document).on('click', '#Approve1', function() {
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
        success: function(data) {
            $('#Approved .approveMsg').text("The record has been approved and successfully published on the website.");
            $('#Approved').show();
            // $('#Approved').modal({
            //     backdrop: 'static',
            //     keyboard: false
            // });
            $('#Approved').modal('show');
            
            $(document).on('click', '#ApprovedSuccess', function() {
                location.reload();
            });
        }
    });
}

function rollbackToPreviousVersion(id) {
    $('#Rollback .rollbackMsg').text("Are you sure you want to rollback this record to previous version? Click on Yes to confirm.");
    $('#ConfirmRollback').show();
    // $('#Rollback').modal({
    //     backdrop: 'static',
    //     keyboard: false
    // });
    $('#Rollback').modal('show');

    $(document).on('click', '#ConfirmRollback', function() {
        rollbackRecord(id);
    });
}

function rollbackRecord(id) {
    $.ajax({
        type: "POST",
        url: rollbackRoute,
        data: {
            'id': id
        },
        async: false,
        success: function(data) {
            location.reload();
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
    // $('#CmsPageComments1').modal({
    //     backdrop: 'static',
    //     keyboard: false
    // });
    $('#CmsPageComments1').modal('show');
    
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