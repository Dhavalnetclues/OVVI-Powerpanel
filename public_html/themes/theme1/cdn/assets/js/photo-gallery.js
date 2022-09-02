$(document).on("click", '.ac-pagination li a', function (event) {
	event.preventDefault();
	var $this = $(this);
	var pagginationUrl = $this.attr('href');
		
		var monthid = "";
		var yearid = "";
		
		$.ajax({
				type: 'GET',
				start: SetBackGround(),
				url: pagginationUrl,
				dataType: "json",
				success: function (data) {
						UnSetBackGround();
						if (data.length == 0) {
						} else {
							$('.section_node').html(data.html);
								//$(data.html).insertAfter($('.section_node').last());
								$("#custompagination").html(data.paginationHtml);
                                                                   $(window).scrollTop(0);
						}
				},
				complete: function () {
				},
				error: function (data) {
				},
		});
});