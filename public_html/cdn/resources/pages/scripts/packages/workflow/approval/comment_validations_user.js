var Validate_user_Comments = function () {
    var handleCommentsForm = function () {
        $(".CommentsForm").validate({
            errorElement: 'span', //default input error message container
            errorClass: 'help-block', // default input error message class
            ignore: [],
            rules: {
                CmsPageComments_user: {
                    required: true,
                },
            },
            messages: {
                CmsPageComments_user: Lang.get('validation.required', {
                    attribute: Lang.get('Comments')
                }),
            },
            errorPlacement: function (error, element) {
                error.insertAfter(element);
            },
            submitHandler: function (form) {
                insertComents();
                return false;
            }
        });

    }
    return {
        //main function to initiate the module
        init: function () {
            handleCommentsForm();
        }
    };
}();
function insertComents() {
    var id = document.getElementById('id').value;
    var intRecordID = document.getElementById('intRecordID').value;
    var fkMainRecord = document.getElementById('fkMainRecord').value;
    var varModuleNameSpace = document.getElementById('varModuleNameSpace').value;
    var intCommentBy = document.getElementById('intCommentBy').value;
    var varModuleTitle = document.getElementById('varModuleTitle').value;
    var CmsPageComments_user = document.getElementById('CmsPageComments_user').value;


    $.ajax({
        type: "POST",
        url: window.site_url+"/powerpanel/dashboard/InsertComments_user",
        data: {'id': id, 'intRecordID': intRecordID, 'fkMainRecord': fkMainRecord, 'varModuleNameSpace': varModuleNameSpace,'CmsPageComments_user':CmsPageComments_user ,'intCommentBy': intCommentBy, 'varModuleTitle': varModuleTitle},
        async: false,
        success: function (data)
        {
            $('#CmsPageComments1User').hide();
            $('#CommentAdded_user .CommentAdded_user').text("Comment added successfully.");
            $('#CommentAdded_user').show();
            $('#CommentAdded_user').modal({
                backdrop: 'static',
                keyboard: false
            });
            location.reload();
        }
    });
}
jQuery(document).ready(function () {
    Validate_user_Comments.init();
});

$('input[name=CmsPageComments]').change(function () {
    var CmsPageComments = $(this).val();
    var trim_CmsPageComments = CmsPageComments.trim();
    if (trim_CmsPageComments) {
        $(this).val(trim_CmsPageComments);
        return true;
    }
});