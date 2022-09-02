//pagination
// $(document).on("click", '#paginationLink li a', function(event) {
//     let pageNumber = $(this).data('page')
//     let title = $(this).attr('title')
//     if (pageNumber != null && pageNumber != '') {
//         var urlParam = pageNumber.split("?")[1];
//         var params = urlParam.split("=")[1];
//         var list = $('#paginationLink li')
//         for (var i = 0; i < list.length; i++) {
//             $(list[i]).removeClass('active')
//             if ($(list[i].getElementsByTagName("a")).attr('title') == params) {
//                 $(list[i]).addClass('active')
//             }
//         }
//         getFilterData(params)
//     }

// })

//sort filter
$(document).on("change", '#sortFilter', function(event) {
    event.preventDefault();
    let sortValue = event.target.value

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

//sort filter
$(document).on("change", '#eventCategory', function(event) {
    event.preventDefault();
    let eventCategory = event.target.value

    getFilterData()
});

//date filter
$(document).on("change", '#dateFilter', function(event) {
    event.preventDefault();
    let dateFilter = event.target.value

    getFilterData()
});


// ajax call to get filter data
function getFilterData() {
    let sortVal = $('#sortFilter').find(":selected").val();
    let eventCategory = $('#eventCategory').find(":selected").val();
    let dateFilter = $('#dateFilter').find(":selected").val();
    let category = $('#categoryFilter li').find('.active').attr('title')
    let year = []
    $('#yearFilter li input[type=checkbox]:checked').each(function() {
        year.push($(this).val())
    });
    var ajaxurl = site_url + '/events/fetchdata';
    $.ajax({
        url: ajaxurl,
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        data: {
            category: category,
            pageName: 'events',
            textDescription: textDescription,
            sortVal: sortVal,
            dateFilter: dateFilter,
            eventCategory: eventCategory,
            year: year
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