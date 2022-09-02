$(document).on("click", '#paginationLink li a', function(event) {
    let pageNumber = $(this).data('page')
    let title = $(this).attr('title')
    if (pageNumber != null && pageNumber != '') {
        var urlParam = pageNumber.split("?")[1];
        var params = urlParam.split("=")[1];
        var list = $('#paginationLink li')
        for (var i = 0; i < list.length; i++) {
            $(list[i]).removeClass('active')
            if ($(list[i].getElementsByTagName("a")).attr('title') == params) {
                $(list[i]).addClass('active')
            }
        }
        getFilterData(params)
    }

})

$(document).on("change", '#monthFilter', function(event) {
    event.preventDefault();
    getFilterData()
});

$(document).on("change", '#categoryFilter', function(event) {
    event.preventDefault();
    getFilterData()
});


$(document).on("click", '#yearFilter li input', function (event) {
    // $('#yearFilter li input[type=checkbox]:checked').each(function() {
    getFilterData()
    // });

});

function getFilterData(pageNumber = 1) {
    let month = $('#monthFilter').find(":selected").val();
    let category = $('#categoryFilter').find(":selected").val();
    let year = []
    $('#yearFilter li input[type=checkbox]:checked').each(function() {
        year.push($(this).val())
    });
    var ajaxurl = site_url + '/public-record-of-key-topics/fetchdata';
    $.ajax({
        url: ajaxurl,
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        data: {
            category: category,
            pageName: 'public-record-of-key-topics',
            textDescription: textDescription,
            pageNumber: pageNumber,
              limits: Limits,
            month: month,
            year: year
        },
        type: "POST",
        dataType: "json",
        success: function(data) {
            if (data.response == '') {
                document.getElementById('pageContent').innerHTML = `<div class="row">
                <div class="col-12 text-center" data-aos="fade-up">
                    <div class="nqtitle n-fw-800 n-lh-110 text-uppercase">No data found</div>
                    <div class="desc n-fs-20 n-fw-500 n-lh-130">Please reset filter or load page to see the data.</div>
                </div>  
            </div>`
            } else {
                document.getElementById('pageContent').innerHTML = data.response
            }
            aosFunction();
            svgIcon(".n-icon");

        },
        complete: function() {},
        error: function(err) {
            console.log('error!', err);
        }
    });
}


$.ajaxSetup({
    headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    }
});
function setDocumentHitCounter(docId, counterType) {
    if (docId != null) {
        
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

$(document).on("click", ".docHitClick", function () {
    var docViewId = $(this).data('viewid');
    var docViewType = $(this).data('viewtype');
    
    if (docViewId != "" && docViewType != "") {
        setDocumentHitCounter(docViewId, docViewType);
    }
});