var Validate_comments = function () {
    var handleCommentsForm = function () {
        $(".CommentsForm").validate({
            errorElement: 'span', //default input error message container
            errorClass: 'help-block', // default input error message class
            ignore: [],
            rules: {
                CmsPageComments: {
                    required: true,
                    noSpace:true
                },
            },
            messages: {
                CmsPageComments: 'This field is required',
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
    var namespace = document.getElementById('namespace').value;
    var CmsPageComments = document.getElementById('CmsPageComments').value;
    var UserID = document.getElementById('UserID').value;
    var fkMainRecord = document.getElementById('fkMainRecord').value;
    var varModuleTitle = document.getElementById('varModuleTitle').value;
    var varModuleId = document.getElementById('varModuleID').value;
    $.ajax({
        type: "POST",
        url: window.site_url + "/powerpanel/workflow/insertComents",
        data: {'id': id, 'namespace': namespace, 'CmsPageComments': CmsPageComments, 'UserID': UserID, 'fkMainRecord': fkMainRecord, 'varModuleTitle': varModuleTitle,'varModuleId':varModuleId},
        async: false,
        success: function (data)
        {
            $('#CmsPageComments1').hide();
            $('#CommentAdded .CommentAdded').text("Comment added successfully.");
            $('#CommentAdded').show();
            // $('#CommentAdded').modal({
            //     backdrop: 'static',
            //     keyboard: false
            // });
            $('#CommentAdded').modal('show');
            location.reload();
        }
    });
}
jQuery(document).ready(function () {
    Validate_comments.init();
});

$('input[name=CmsPageComments]').change(function () {
    var CmsPageComments = $(this).val();
    var trim_CmsPageComments = CmsPageComments.trim();
    if (trim_CmsPageComments) {
        $(this).val(trim_CmsPageComments);
        return true;
    }
});