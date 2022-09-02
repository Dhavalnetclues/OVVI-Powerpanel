$(document).ready(function() {
	var owlSlideNumber = 1, owlClassName = '.inner_banner_01';
	var owlLoop = false, owlItemLength = $(owlClassName + ' .owl-carousel .item').length;        
	if (owlItemLength < owlSlideNumber) { $(owlClassName).find('.owl-controls').css('display', 'none');}
	if (owlItemLength > owlSlideNumber) { owlLoop = true; } else { owlLoop = false; }

	$(owlClassName + ' .owl-carousel').owlCarousel({
		loop: false,
		mouseDrag: false,
		nav:true,
		touchDrag: true,
		margin: 0,
		autoplay: true,
		autoplayTimeout: 4000,
		smartSpeed: 1000,
		autoplayHoverPause: true,
		thumbs: false,
		thumbImage: false,
		thumbContainerClass: 'owl-thumbs',
		thumbItemClass: 'owl-thumb-item',
		dotsEach: true,
		dots:false,
		navText: ["&#8249;","&#8250;"],
		responsive:{
				0:{items: 1, dots: false, nav: true},
				600:{items: 1, dots: false, nav: true},
				768:{items: owlSlideNumber, dots: false, nav: true},
				1024:{items: owlSlideNumber, dots: false, nav: true }
		}
	});
});
var hashChange = true;
			$(".pagination li").each(function() {
							var title = $(this).text();
							if (title == '«') {
											title = "Previous";
							} else if (title == '»') {
											title = "Next";
							}
							$(this).attr('title', title);
			});
			$(window).on('hashchange', function() {
							if (window.location.hash) {
											var page = window.location.hash.replace('#', '');
											if (page == Number.NaN || page <= 0) {
															return false;
											} else {
															getPosts(page);
											}
							}
			});
			$(document).on('click', '.pagination a', function(e) {
							var page = $(this).attr('href').split('page=')[1];
							window.location.hash = page;
							e.preventDefault();
			});

			function getPosts(page) {
							$.ajax({
											url: '?page=' + page,
											dataType: 'HTML',
							}).done(function(data) {
											$('.posts').html(data);
											window.location.hash = page;
											var href = window.location.href;                
							}).fail(function() {
											alert('Posts could not be loaded.');
							});
			}