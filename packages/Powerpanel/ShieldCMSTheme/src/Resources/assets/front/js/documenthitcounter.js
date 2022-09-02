$.ajaxSetup({
    headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    }
});
function setDocumentHitCounter(docId, counterType) {
    if (docId != null) {
        if (viewingPreview == "false" || !viewingPreview) {
            $.ajax({
                type: 'POST',
                url: site_url + "/setDocumentHitcounter",
                data: {
                    "docId": docId,
                    "counterType": counterType
                },
                success: function (data) {

                },
                complete: function () {

                },
                error: function (data) {
                },
            });
        }
    }
}

$(document).on("click", ".docHitClick", function () {
    var docViewId = $(this).data('viewid');
    var docViewType = $(this).data('viewtype');
    if (docViewId != "" && docViewType != "") {
        setDocumentHitCounter(docViewId, docViewType);
    }
});