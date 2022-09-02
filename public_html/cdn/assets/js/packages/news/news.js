$(document).on("click", '.ac-pagination li a', function(event) {
    event.preventDefault();
    var $this = $(this);
    var pagginationUrl = $this.attr('href');

    var monthid = "";
    var yearid = "";

    $.ajax({
        type: 'GET',
        start: SetBackGround(),
        //url: ajaxModuleUrl + "?page=" + pageNumber,
        url: pagginationUrl,
        dataType: "json",
        success: function(data) {
            UnSetBackGround();
            //pageNumber += 1;
            if (data.length == 0) {} else {
                $('.section_node').html(data.html);
                //$(data.html).insertAfter($('.section_node').last());
                $("#custompagination").html(data.paginationHtml);
            }

        },
        complete: function() {},
        error: function(data) {},
    });
});

//pagination
var c = parseInt(Limits);
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

        c += parseInt(Limits);
        getFilterData(1, c);

    }

})

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

// year filter
$(document).on("click", '#yearFilter li input', function(event) {
    // $('#yearFilter li input[type=checkbox]:checked').each(function() {
    getFilterData()
        // });

});

// $(document).on("click", ".LimitExpend", function() {
//     var Limits = '30';
//     getFilterData(1, Limits);
// });

// ajax call to get filter data
function getFilterData(pageNumber = 1, Limits) {
    let sortVal = $('#sortFilter').find(":selected").val();
    let category = $('#categoryFilter li').find('.active').attr('title')
    let year = []
    $('#yearFilter li input[type=checkbox]:checked').each(function() {
        year.push($(this).val())
    });
    var search_action = $('#searchfilter').val();
    var ajaxurl = site_url + '/news/fetchdata';
    $.ajax({
        url: ajaxurl,
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        data: {
            category: category,
            pageName: 'news',
            limits: Limits,
            textDescription: textDescription,
            pageNumber: pageNumber,
            sortVal: sortVal,
            year: year,
            search_action: search_action
        },
        type: "POST",
        dataType: "json",
        success: function(data) {
            if (data.response == '') {
                document.getElementById('pageContent').innerHTML = `<div class="row">
                <div class="col-12 text-center" data-aos="fade-up">
                    <div class="nqtitle n-fw-800 n-lh-110 text-uppercase">No data found</div>
                    <div class="n-fw-800 n-lh-110 text-uppercase">Please reset filter or load page to see the data.</div>
                </div>
            </div>`
            } else {
                document.getElementById('pageContent').innerHTML = data.response
            }
            aosFunction();
            svgIcon(".n-icon");
            dataThum(".ac-webp, .thumbnail-container");
        },
        complete: function() {},
        error: function(err) {
            console.log('error!', err.Message);
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
            success: function(data) {

            },
            complete: function() {

            },
            error: function(data) {},
        });

    }
}

$(document).on("click", ".docHitClick", function() {
    var docViewId = $(this).data('viewid');
    var docViewType = $(this).data('viewtype');

    if (docViewId != "" && docViewType != "") {
        setDocumentHitCounter(docViewId, docViewType);
    }
});

$(".newsTagForMove").click(function() {
    $('html, body').animate({
        scrollTop: $(".news-info").offset().top
    }, 1000);
});
// $("#paginationLink").click(function() {
//     $('html, body').animate({
//         scrollTop: $(".news-info").offset().top
//     }, 1000);
// });

$(document).on('keyup', '#searchfilter', function(e) {
    getFilterData();
});