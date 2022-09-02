$(document).on("click", "#paginationLink li a", function(event) {
    let pageNumber = $(this).data("page");
    let title = $(this).attr("title");
    if (pageNumber != null && pageNumber != "") {
        var urlParam = pageNumber.split("?")[1];
        var params = urlParam.split("=")[1];
        var list = $("#paginationLink li");
        for (var i = 0; i < list.length; i++) {
            $(list[i]).removeClass("active");
            if ($(list[i].getElementsByTagName("a")).attr("title") == params) {
                $(list[i]).addClass("active");
            }
        }
        getFilterData(params);
    }
});

$(document).on("click", '#searchBtn', function(event) {
    event.preventDefault();
    getFilterData()
});

function getFilterData(pageNumber = 1) {
    let searchValue = $('#search').val();
    var ajaxurl = site_url + "/fm-broadcasting-stations/fetchdata";
    $.ajax({
        url: ajaxurl,
        headers: {
            "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
        },
        data: {
            pageName: "fm-broadcasting-stations",
            pageNumber: pageNumber,
            searchValue: searchValue
        },
        type: "POST",
        dataType: "json",
        success: function(data) {
            console.log("resp", data);
            if (data.response == "") {
                document.getElementById(
                    "pageContent"
                ).innerHTML = `<h2>No Record Found</h2>`;
            } else {
                document.getElementById("pageContent").innerHTML = data.response;
            }
            aosFunction();
            SVGConverter(".nq-svg");
        },
        complete: function() {},
        error: function(err) {
            console.log("error!", err);
        },
    });
}