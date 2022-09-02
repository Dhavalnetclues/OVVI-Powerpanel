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

//sort filter
$(document).on("click", '#consultationTypeFilter li a', function(event) {
    event.preventDefault();

    var list = $('#consultationTypeFilter li')
    for (var i = 0; i < list.length; i++) {
        $(list[i].getElementsByTagName("a")).removeClass('active')
    }

    $(event.target).addClass('active')

    getFilterData()
});

// category filter
$(document).on("click", '#categoryFilter li a', function(event) {
    event.preventDefault();
    let selectedCategory = event.target.title

    var list = $('#categoryFilter li')
    for (var i = 0; i < list.length; i++) {
        $(list[i].getElementsByTagName("a")).removeClass('active')
    }

    $(event.target).addClass('active')
    getFilterData()
});

// year filter
$(document).on("click", '#yearFilter li input', function(event) {
    // $('#yearFilter li input[type=checkbox]:checked').each(function() {
    getFilterData()
        // });

});

// ajax call to get filter data
function getFilterData(pageNumber = 1) {
    let consultationType = $('#consultationTypeFilter li').find('.active').attr('title')
    let category = $('#categoryFilter li').find('.active').attr('title')
    let year = []
    $('#yearFilter li input[type=checkbox]:checked').each(function() {
        year.push($(this).val())
    });
    var ajaxurl = site_url + '/consultations/fetchdata';
    $.ajax({
        url: ajaxurl,
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        data: {
            category: category,
            pageName: 'consultations',
            textDescription: textDescription,
            pageNumber: pageNumber,
            consultationType: consultationType,
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
            SVGConverter(".nq-svg");

        },
        complete: function() {},
        error: function(err) {
            console.log('error!', err);
        }
    });
}