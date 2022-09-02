$(document).ready(function () {
alert()

//                                $(".kt-portlet__foot .multi_upload_images .document_manager").click(function() {
//                                $('.kt-portlet__foot #imageuploaddiv').hide();
//                                });
//                                $(".kt-portlet__foot .multi_upload_images .media_manager").click(function() {
//                                $('.kt-portlet__foot #fileuploaddiv').hide();
//                                });
//                                                                ************Forqord************
    $('body').on('click', '#gallary_component > .portlet-title > .tools > a.remove', function (e) {
        getmessage($('#toid').val());
    });
    $('body').on('click', '#document_component > .portlet-title > .tools > a.remove', function (e) {
        getmessage($('#toid').val());
    });
    $('#ForwordUserListData .modal-dialog .fwd-done').click(function () {
        if (($(this).attr("disabled") == 'disabled')) {
            return false;
        }
        $("#ForwordUserListData").modal("hide");
    });
    $('#ForwordUserListData .login_user li .f-send').click(function () {
        if (($(this).attr("disabled") == 'disabled')) {
            return false;
        }
        var toid = $(this).attr('id');
        $(this).attr("disabled", true);
        $('#ForwordUserListData .modal-dialog .fwd-done').removeClass("disabled");
        $(this).html('<i class="fa fa-check" aria-hidden="true"></i> Sent');
        $(this).attr('title', 'Sent');
        $('.login-user-popup .login_user li a#userid_' + toid).addClass("disabled");
        var fromid = dataid;
        var recordid = $('.modal.login-user-popup .modal-body form #forwordRecId').val();
        var varforquatnew = $('.modal.login-user-popup .modal-body form #varforquatnew').val();
        var newmsg = $('#new_forword_search_msg').val();
        var ajaxurl = window.site_url + '/powerpanel/messagingsystem/forwordtomessage';
        $.ajax({
            url: ajaxurl,
            async: false,
            data: {
                recordid: recordid,
                varforquatnew: varforquatnew,
                toid: toid,
                newmsg: newmsg,
                fromid: fromid
            },
            type: "POST",
            success: function (data) {

            }
        });
    });
//  *************Multiple Msg Remove************
    $("a#MulRemoveMsg").click(function () {
        $("#Sing_Remove_Msg").modal("show");
    });
//  **************Cancel**************
    $("a#msg_cancel").click(function () {
        $("#Sing_Remove_Msg").modal("hide");
        $('div.romove_button').hide();
    });
//  **************REMOVE**************
    $("a#msg_remove").click(function () {
        $("#Sing_Remove_Msg").modal("hide");
        $('.message_loader').show();
        var ajaxurl = window.site_url + '/powerpanel/messagingsystem/removesinglemsg';
        var removemsgid = $('form#singlemsgremove #removemsgid').val();
        var fromid = dataid;
        var toid = $('#toid').val();
        $.ajax({
            url: ajaxurl,
            async: false,
            data: {
                removemsgidvalue: removemsgid,
                toid: toid,
                fromid: fromid
            },
            type: "POST",
            success: function (data) {
                var data = data.split(',');
                jQuery.each(data, function (i, val) {
                    $('.kt-chat .kt-portlet__body .kt_remove_msg_' + val + '').remove();
                });
                $('div.romove_button').hide();
                $('.select-main-msg').hide();
                $('.sel-message').hide();
                $('.kt-chat .kt-portlet__foot').show();
                setTimeout(function () {
                    $('.message_loader').hide();
                }, 1500);
            }
        });
    });
//   ****************CloseMulRemovePopup
    $(".select-main-msg .top-select .select-close").click(function () {
        $('.select-main-msg').hide();
        $('.sel-message').hide();
        $('.massage_system .kt-chat .romove_button').hide();
        $('.kt-chat .kt-portlet__foot').show();
    });
});
//   ****************Multiple Remove MSG
function checkckeckbox()
{
    var check = $('.sel-message label.message-check input[type="checkbox"]:checked').length;
    $('.select-main-msg .top-select span#countmsg').html('<span>' + check + '</span> Message Selected')
    if (check == 0) {
        $('.massage_system .kt-chat .romove_button').hide();
        $('.kt-chat .kt-portlet__foot').show();
        $('div.sel-message').hide();
        $('.select-main-msg').hide();
    } else {
        $('.massage_system .kt-chat .romove_button').show();
        $('.kt-chat .kt-portlet__foot').hide();
        $('div.select-main-msg').show();
        $('.select-main-msg').show();
    }
    var arraycheckedval = []
    var checkboxes = document.querySelectorAll('input[type=checkbox]:checked')
    for (var i = 0; i < checkboxes.length; i++) {
        arraycheckedval.push(checkboxes[i].value)
    }
    $('form#singlemsgremove #removemsgid').val(arraycheckedval);
}
function JumpIntoUser(id)
{
    $("#UserListData").modal("hide");
    $('.chat_sys_list li[data-userid=' + id + ']').addClass('active');
    $("#replayform").show();
    $("#toid").val(id);
    getmessage(id);
}
//  ******************Clear Chat*************


function opencontextMenuRight(event)
{
    event.preventDefault();
    $('.context-menu-admin').contextMenu();
}
function opencontextMenuLeft(event)
{
    event.preventDefault();
    $('.context-menu-user').contextMenu();
}

$(function () {
    $('textarea').emoji({place: 'after'});
});

$(document).ready(function () {
    setInterval(function () {
        $('.addhiglight').closest("td").closest("tr").addClass('higlight');
    }, 800);
    $(".mcscroll").mCustomScrollbar({
        axis: "y",
        scrollbarPosition: "outside",
        theme: "minimal-dark"
    });
    $(".mcscroll2").mCustomScrollbar({
        axis: "y",
        scrollbarPosition: "outside",
        theme: "minimal-dark"
    });
});
$(document).on('click', 'ul.chat_sys_list li', function () {
    $("#replayform").show();
    var id = $(this).data("userid");
    $("#toid").val(id);
    getmessage(id);
});
function getmessage(userid) {
    var fromid = dataid;
    var ajaxurl = window.site_url + '/powerpanel/messagingsystem/messageiddata';
    $.ajax({
        url: ajaxurl,
        async: false,
        data: {
            toid: userid,
            fromid: fromid
        },
        type: "POST",
        success: function (data) {
            $('div#newMSG_' + userid + ' #msg-number').fadeOut(1800, function () {
                $('div#newMSG_' + userid + ' #msg-number').remove();
            });
            $('#htmldata').html(data);
            $(".mcscroll").mCustomScrollbar({
                axis: "y",
                scrollbarPosition: "outside",
                theme: "minimal-dark"
            });
            $(".mcscroll").mCustomScrollbar("scrollTo", "last");
            setTimeout(function () {
                $('.message_loader').hide();
            }, 1500);
        }
    });
}
function startChat(fromid) {
    $("#UserListData").modal("show");
//    var ajaxurl = window.site_url + '/powerpanel/messagingsystem/recentid';
//    $.ajax({
//    url: ajaxurl,
//            async: false,
//            data: {
//            fromid: fromid
//            },
//            type: "POST",
//            success: function(data) {
//            if (data != 0) {
//            $('.chat_sys_list li[data-userid=' + data + ']').addClass('active');
//            getmessage(data)
//                    $("#replayform").show();
//            $("#toid").val(data);
//            } else {
//            return false;
//            }
//            $(".mcscroll2").mCustomScrollbar({
//            axis: "y",
//                    scrollbarPosition: "outside",
//                    theme: "minimal-dark"
//            });
//            }
//    });
}
$(document).ready(function () {
    $(document).on('click', '#btnSubmit', function () {
        var comment = $.trim($("#varShortDescription").val());
        var docid = $("#publications").val();
        var imageId = $("#publications_image").val();
        if (comment == '' && docid == '' && imageId == '') {
            $(".errorclass").show();
            $("form#MsgSystem div").addClass("has-error");
            $(".errorclass").html("Please type message or select any document before you click on reply.");
            return false;
        }
        $(".errorclass").hide();
        $("form#MsgSystem div:first").removeClass("has-error");
        var ajaxurl = window.site_url + '/powerpanel/messagingsystem/insermessagedata';
        $('.message_loader').show();
        $.ajax({
            url: ajaxurl,
            data: $('form#MsgSystem').serialize(),
            type: "POST",
            success: function (data) {
                var data = data.split('@@');
                $('ul.chat_sys_list').html(data[1]);
                $('ul.chat_sys_list li[data-userid=' + data[0] + ']').addClass('active');
                getmessage(data[0]);
                $("#varShortDescription").val('');
                $(".emoji-wysiwyg-editor.form-control").html('');
                $("#publications").val('');
                $("#publications_image").val('');
                $("#publications_documents").html('');
                $("#publications_image_img").html('');
                $("#replayform").show();
                $(".mcscroll2").mCustomScrollbar({
                    axis: "y",
                    scrollbarPosition: "outside",
                    theme: "minimal-dark"
                });
            }
        });
    });
    //        **************EDIT****************
    $(document).on('click', '#btnEdit', function () {
        var comment = $.trim($("#varShortDescription").val());
        var docid = $("#publications").val();
        var imageId = $("#publications_image").val();
        if (comment == '' && docid == '' && imageId == '') {
            $(".errorclass").show();
            $("form#MsgSystem div").addClass("has-error");
            $(".errorclass").html("Please type message or select any document before you click on reply.");
            return false;
        }
        $(".errorclass").hide();
        $("form#MsgSystem div:first").removeClass("has-error");
        var toid = $("#toid").val();
        var ajaxurl = window.site_url + '/powerpanel/messagingsystem/insermessagedata';
        $('.message_loader').show();
        $.ajax({
            url: ajaxurl,
            data: $('form#MsgSystem').serialize(),
            type: "POST",
            success: function (data) {
                var data1 = data.split('@@');
                if (($("#formtype").val() == 'edit')) {
                    $('span#' + data1[0]).find("i").remove();
                    $('#html_chat_dis_' + data1[0]).before('<i class="ri-pencil-line" style="" title="This message has been edited."></i>');
                    var divHtml = $('div#html_chat_dis_' + data1[0]).html(data1[1]);
                    $('div#html_chat_' + data1[0]).html(data1[1]);
                    $("#formtype").val('add');
                    $("#varShortDescription").val('');
                    $("#publications").val('');
                    $('#publications_image').val('');
                    $("#publications_documents").html('');
                    $('#publications_image_img').html('');
                    $("#replayform").show();
                    $('.message_loader').hide();
                    getmessage(toid)
                }
                if (($("#formtype").val() == 'add')) {
                    $("#formtype").val('add');
                    $("#varShortDescription").val('');
                    $("#publications").val('');
                    $("#publications_documents").html('');
                    $('#publications_image_img').html('');
                    $('#publications_image').val('');
                    $("#replayform").show();
                    $('.message_loader').hide();
                    getmessage(toid)
                }
                if (($("#formtype").val() == 'quote')) {
                    $("#formtype").val('add');
                    $("#varShortDescription").val('');
                    $("#publications").val('');
                    $('#publications_image').val('');
                    $("#publications_documents").html('');
                    $('#publications_image_img').html('');
                    $("#replayform").show();
                    getmessage(toid);
                    $('.message_loader').hide();
                }
                $('.message_loader').hide();
            }
        });
    });
});
$(document).ready(function () {
    $(document).on('click', '#refersh', function () {
        $(".chat_sys_list li.active").each(function (index, value) {
            var userid = $(this).attr("data-userid");
            var fromid = dataid;
            var ajaxurl = window.site_url + '/powerpanel/messagingsystem/getnewmessage';
            $.ajax({
                url: ajaxurl,
                async: false,
                data: {
                    toid: userid,
                    fromid: fromid
                },
                type: "POST",
                success: function (data) {
                    getmessage(userid);
                    $("#replayform").show();
                    $(".mcscroll2").mCustomScrollbar({
                        axis: "y",
                        scrollbarPosition: "outside",
                        theme: "minimal-dark"
                    });
                }
            });
        });
    });
});
$(document).ready(function () {
    setInterval(function () {
        refreshauto()
    }, 10000);
});
function refreshauto() {
    var useridarray = [];
    var i = 0;
    var activeuserid = '';
    $(".chat_sys_list li").each(function (index, value) {
        if ($(this).hasClass('active') == true) {
            activeuserid = $(this).attr("data-userid");
        }
        useridarray[i++] = $(this).attr("data-userid");
    });
    var fromid = dataid;
    var userid = useridarray;
    var ajaxurl = window.site_url + '/powerpanel/messagingsystem/getnewmessagecounter';
    $.ajax({
        url: ajaxurl,
        async: false,
        data: {
            toid: userid,
            activeuserid: activeuserid,
            fromid: fromid
        },
        type: "POST",
        success: function (datacount) {
            var activeid = [];
            var htmllist = datacount.split('!!');
            $(".chat_sys_list li").each(function (index, value) {
                if ($(this).hasClass('active') == true) {
                    activeid = $(this).attr("data-userid");
                }
            });
            $('ul.chat_sys_list').html(htmllist[2]);
            if (activeid != '') {
                $('ul.chat_sys_list li[data-userid=' + activeid + ']').addClass('active');
            }
            $(".chat_sys_list li").each(function (index, item) {
                var data = datacount.split('@@')
                var count = data[index];
                var userid = $(this).attr("data-userid");
                if ($(this).hasClass('active') == true && count > 0) {
                    getmessage(userid);
                }
                if ($(this).hasClass('active') == true && htmllist[3] > 0) {
                    getmessage(userid);
                }

            });
            $(".mcscroll2").mCustomScrollbar({
                axis: "y",
                scrollbarPosition: "outside",
                theme: "minimal-dark"
            });
        }
    });
}
$('#varShortDescription').keyup(function (e) {
    if ($(this).val() == '' && $("#publications").val() == '' && $("#publications_image").val() == '') {
        $(".errorclass").show();
        $("form#MsgSystem div").addClass("has-error");
        $(".errorclass").html("Please type message or select any document before you click on reply.");
    } else {
        $(".errorclass").hide();
        $("form#MsgSystem div:first").removeClass("has-error");
    }
});
$(document).ready(function () {
    $("#search_msg").on("keyup", function () {
        var value = $(this).val().toLowerCase();
        $("ul.chat_sys_list li").filter(function () {
            $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1)
        });
    });
    $("#forword_search_msg").on("keyup", function () {
        var value = $(this).val().toLowerCase();
        $("#ForwordUserListData .login_user li").filter(function () {
            $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1)
        });
    });
    $(".form-group.form-md-line-input .form-control").keypress(function (e) {
        if (e.which == 13) {
            var formid = jQuery('.kt-portlet__foot .kt_chat__actions .btn').attr("id");
            jQuery('#' + formid).focus().click();
        }
    });
});

$(function () {
    $.contextMenu({
        selector: '.context-menu-admin',
        callback: function (key, options) {
            var spanId = $(this).attr('id');
            if (key == 'edit') {

                var imageid = $(this).attr('data-image');
                var fileid = $(this).attr('data-file');
                if (imageid != undefined && fileid == undefined)
                {
                    $('.kt-portlet__foot .multi_upload_images #publications_image').val(imageid);
                    MediaManager.open('publications_image');
                    $("body .media_manager").click();
                    $('#formtype').val('edit')
                    $('#editId').val(spanId);
                    $('button#btnSubmit').attr('id', 'btnEdit');
                    var divHtml = $('div#html_chat_' + spanId).html();
                    $('#varShortDescription').val(divHtml).remove('.btn_group');
                } else if (fileid != undefined && imageid != undefined)
                {
                    alert('You cannot edit files and images simultaneously.');
                } else if (fileid != undefined && imageid == undefined)
                {
                    $('.kt-portlet__foot .multi_upload_images #publications').val(fileid);
                    MediaManager.openDocumentManager('publications');
                    $("body .document_manager").click();
                    $('#formtype').val('edit')
                    $('#editId').val(spanId);
                    $('button#btnSubmit').attr('id', 'btnEdit');
                    var divHtml = $('div#html_chat_' + spanId).html();
                    $('#varShortDescription').val(divHtml).remove('.btn_group');
                } else {
                    $('#formtype').val('edit')
                    $('#editId').val(spanId);
                    $('button#btnSubmit').attr('id', 'btnEdit');
                    var divHtml = $('div#html_chat_' + spanId).html();
                    $('#varShortDescription').val(divHtml).remove('.btn_group');
                }
            }
            if (key == 'copy') {
                $('.portlet .kt-chat .msg-copy').show();
                var divHtml = $('div#html_chat_' + spanId).html();
                var aux = document.createElement("input");
                aux.setAttribute("value", divHtml);
                document.body.appendChild(aux);
                aux.select();
                document.execCommand("copy");
                document.body.removeChild(aux);
                $('.portlet .kt-chat .msg-copy').fadeOut(2500, function () {});
            }
            if (key == 'quote') {
                $('#formtype').val('quote')
                $('#editId').val(spanId);
                $('button#btnSubmit').attr('id', 'btnEdit');
                var divHtml = $('div#html_chat_' + spanId).html();
                $('#varShortDescription').val(divHtml).remove('.btn_group');
            }

            if (key == 'remove') {
                $('form#singlemsgremove #removemsgid').val(spanId);
                $('#Sing_Remove_Msg').modal('show');
            }
            if (key == 'selmsg') {
                $('div.sel-message').show();
                $('div.romove_button').show();
                $('div.select-main-msg').show();
                $(".sel-message label.message-check :checkbox[value=" + spanId + "]").prop("checked", "true");
                $('form#singlemsgremove #removemsgid').val(spanId);
                $('.kt-chat .kt-portlet__foot').hide();
            }
            if (key == 'forword') {

                var quateid = $(this).attr('data-id');
                $('#ForwordUserListData').modal('show');
                var divHtml = $('div#html_chat_' + spanId).html();
                if (divHtml == '') {
                    $('.modal.login-user-popup .modal-body form #varforquatnew').val('Y');
                } else {
                    $('.modal.login-user-popup .modal-body form #varforquatnew').val('N');
                }
                if (quateid != undefined) {
                    $('.modal.login-user-popup .modal-body form #forwordRecId').val(quateid);
                } else {
                    $('.modal.login-user-popup .modal-body form #forwordRecId').val(spanId);
                }
                $("#ForwordUserListData .forward-type-message .message-title").html('<i class="fa fa-quote-left"></i> ' + divHtml);
                $('form#MsgSystem #toid').val();
                var activeid = $('form#MsgSystem #toid').val();
                $('.login-user-popup .login_user li a').show();
                $('.login-user-popup .login_user li a#userid_' + activeid).hide();
                $('#ForwordUserListData .login_user li .f-send').attr("disabled", false);
                $('#ForwordUserListData .login_user li a').removeClass("disabled");
                $('#new_forword_search_msg').val('');
            }
            if (key == 'quit') {
                getmessage($('#toid').val());
            }
        },
        items: {
            "edit": {
                name: "Edit",
                icon: "edit"
            },
            copy: {
                name: "Copy",
                icon: "fa-copy"
            },
            "quote": {
                name: "Quote",
                icon: "fa-quote-left"
            },
            "forword": {
                name: "Forword",
                icon: "fa-share"
            },
            "selmsg": {
                name: "Select Message",
                icon: "fa-check"
            },
            "remove": {
                name: "Remove",
                icon: "fa-trash"
            },
            "sep1": "---------",
            "quit": {
                name: "Quit",
                icon: function () {
                    return 'context-menu-icon context-menu-icon-quit';
                }
            }
        }
    });
});
$(function () {
    $.contextMenu({
        selector: '.context-menu-user',
        callback: function (key, options) {
            var spanId = $(this).attr('id');
            if (key == 'copy') {
                $('.portlet .kt-chat .msg-copy').show();
                var divHtml = $('div#html_chat_' + spanId).html();
                var aux = document.createElement("input");
                aux.setAttribute("value", divHtml);
                document.body.appendChild(aux);
                aux.select();
                document.execCommand("copy");
                document.body.removeChild(aux);
                $('.portlet .kt-chat .msg-copy').fadeOut(2500, function () {});
            }
            if (key == 'quote') {
                $('#formtype').val('quote')
                $('#editId').val(spanId);
                $('button#btnSubmit').attr('id', 'btnEdit');
                var divHtml = $('div#html_chat_' + spanId).html();
                $('#varShortDescription').val(divHtml).remove('.btn_group');
            }
            if (key == 'forword') {
                var quateid = $(this).attr('data-id');
                $('#ForwordUserListData').modal('show');
                var divHtml = $('div#html_chat_' + spanId).html();
                if (divHtml == '') {
                    $('.modal.login-user-popup .modal-body form #varforquatnew').val('Y');
                } else {
                    $('.modal.login-user-popup .modal-body form #varforquatnew').val('N');
                }
                if (quateid != undefined) {
                    $('.modal.login-user-popup .modal-body form #forwordRecId').val(quateid);
                } else {
                    $('.modal.login-user-popup .modal-body form #forwordRecId').val(spanId);
                }
                $("#ForwordUserListData .forward-type-message .message-title").html('<i class="fa fa-quote-left"></i> ' + divHtml);
                $('form#MsgSystem #toid').val();
                var activeid = $('form#MsgSystem #toid').val();
                $('.login-user-popup .login_user li a').show();
                $('.login-user-popup .login_user li a#userid_' + activeid).hide();
                $('#ForwordUserListData .login_user li .f-send').attr("disabled", false);
                $('#ForwordUserListData .login_user li a').removeClass("disabled");
                $('#new_forword_search_msg').val('');
            }
            if (key == 'quit') {
                getmessage($('#toid').val());
            }
        },
        items: {
            "copy": {
                name: "Copy",
                icon: "fa-copy"
            },
            "quote": {
                name: "Quote",
                icon: "fa-quote-left"
            },
            "forword": {
                name: "Forword",
                icon: "fa-share"
            },
            "sep1": "---------",
            "quit": {
                name: "Quit",
                icon: function () {
                    return 'context-menu-icon context-menu-icon-quit';
                }
            },
        }
    });
    $('.context-menu-user').on('click', function (e) {
        //            alert('clicked', this);
    })
});