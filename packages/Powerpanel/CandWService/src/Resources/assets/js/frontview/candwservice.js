//pagination
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

// year filter
$(document).on("click", '#yearFilter li input', function(event) {
    // $('#yearFilter li input[type=checkbox]:checked').each(function() {
    getFilterData()
        // });

});

$(document).on("change", '#monthFilter', function(event) {
    event.preventDefault();
    getFilterData()
});

// ajax call to get filter data
function getFilterData(pageNumber = 1) {
    let month = $('#monthFilter').find(":selected").val();
    let year = []
    $('#yearFilter li input[type=checkbox]:checked').each(function() {
        year.push($(this).val())
    });
    var ajaxurl = site_url + '/cw-service-filings/fetchdata';
    $.ajax({
        url: ajaxurl,
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        data: {
            pageName: 'cw-service-filings',
            textDescription: textDescription,
            pageNumber: pageNumber,
            month: month,
            year: year
        },
        type: "POST",
        dataType: "json",
        success: function(data) {
            console.log('resp', data)
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