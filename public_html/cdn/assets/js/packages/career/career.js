$(document).on("click", '#paginationLink li a', function (event) {
    let pageNumber = $(this).data('page')
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
});

function getFilterData(pageNumber = 1) {

    var ajaxurl = site_url + '/' + slug + '/fetchdata';
    $.ajax({
        url: ajaxurl,
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        data: {
            pageName: pagename,
            pageNumber: pageNumber
        },
        type: "POST",
        dataType: "json",
        success: function (data) {
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
        complete: function () {},
        error: function (err) {
            console.log('error!', err);
        }
    });
}