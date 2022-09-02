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


$(document).on("change", '#categoryFilter', function(event) {
    event.preventDefault();
    getFilterData()
});

function getFilterData(pageNumber = 1) {

    let category = $('#categoryFilter').find(":selected").val();

    var ajaxurl = site_url + '/number-nxx-allocations/fetchdata';
    $.ajax({
        url: ajaxurl,
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        data: {
            category: category,
            pageName: 'number-nxx-allocations',
            textDescription: textDescription,
            // pageNumber: pageNumber,
        },
        type: "POST",
        dataType: "json",
        success: function(data) {
            if (data.response == '') {
                document.getElementById('pageContent').innerHTML = `<h2>No Record Found</h2>`
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