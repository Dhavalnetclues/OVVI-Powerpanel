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
                $(window).scrollTop(0);
            }

        },
        complete: function() {},
        error: function(data) {},
    });
});

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

$(document).on("change", '#sortFilter', function(event) {
    event.preventDefault();
    let sortValue = event.target.value

    getFilterData()
});

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

$(document).on("click", '#yearFilter li input', function(event) {
    $('#yearFilter li input[type=checkbox]:checked').each(function() {
        getFilterData()
    });

});

function getFilterData(pageNumber = 1) {
    let sortVal = $('#sortFilter').find(":selected").val();
    let category = $('#categoryFilter li').find('.active').attr('title')
    let year = []
    $('#yearFilter li input[type=checkbox]:checked').each(function() {
        year.push($(this).val())
    });
    var ajaxurl = site_url + '/news/fetchdata';
    $.ajax({
        url: ajaxurl,
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        data: {
            category: category,
            pageName: 'news',
            textDescription: textDescription,
            pageNumber: pageNumber,
            sortVal: sortVal,
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