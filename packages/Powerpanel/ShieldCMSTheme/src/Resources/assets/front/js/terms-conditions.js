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
