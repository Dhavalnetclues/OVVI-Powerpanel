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





function getFilterData(pageNumber = 1) {
  
    var ajaxurl = site_url + '/team/fetchdata';
    $.ajax({
        url: ajaxurl,
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        data: {
           
            pageName: 'team',
          
            pageNumber: pageNumber,
           
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