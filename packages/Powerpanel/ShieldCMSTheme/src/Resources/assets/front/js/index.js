$(document).ready(function() {
	var owlSlideNumber = 1, owlClassName = '.banner_02';
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
		thumbs: true,
		thumbImage: true,
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
$(document).ready(function() {
		var owlSlideNumber = 3, owlClassName = '.section_11';
		var owlLoop = false, owlItemLength = $(owlClassName + ' .owl-carousel .item').length;        
		if (owlItemLength < owlSlideNumber) { $(owlClassName).find('.owl-controls').css('display', 'none');}
		if (owlItemLength > owlSlideNumber) { owlLoop = true; } else { owlLoop = false; }

			$(owlClassName + ' .owl-carousel').owlCarousel({
				loop: false,
				nav : true,
				autoplay: true,
				autoplayHoverPause: true,
				dots:true,
				dotsEach: true,
				navText: ["&#8249;","&#8250;"],
				stagePadding: 0,
				center: false,
				mouseDrag: false,
				touchDrag: true,
				margin: 10,
				autoplayTimeout: 4000,
				smartSpeed: 1000, 
				responsive:{
						0:{items: 1, dots: true, nav: true},
						480:{items: 2, dots: true, nav: true},
						768:{items: 2, dots: true, nav: true},
						1024:{items: owlSlideNumber, dots: true, nav: true},
						1025:{items: owlSlideNumber, dots: true, nav: true}
				}
		});        
});
$(document).ready(function() 
				{
					 /* jQuery(document).ready(function( $ ) {
								$('.section_12 .counter').counterUp({
										delay: 10,
										time: 1000
								});
						});*/
						//SVGConverter (".section_12");
				});
$(document).ready(function() {
		var owlSlideNumber = 1, owlClassName = '.section_17';
		var owlLoop = false, owlItemLength = $(owlClassName + ' .owl-carousel .item').length;        
		if (owlItemLength < owlSlideNumber) { $(owlClassName).find('.owl-controls').css('display', 'none');}
		if (owlItemLength > owlSlideNumber) { owlLoop = true; } else { owlLoop = false; }

			$(owlClassName + ' .owl-carousel').owlCarousel({
				loop: false,
				nav : true,
				autoplay: true,
				autoplayHoverPause: true,
				dots:true,
				dotsEach: true,
				navText: ["&#8249;","&#8250;"],
				stagePadding: 0,
				center: false,
				mouseDrag: false,
				touchDrag: true,
				margin: 10,
				autoplayTimeout: 4000,
				smartSpeed: 1000, 
				responsive:{
						0:{items: 1, dots: true, nav: true},
						480:{items: 2, dots: true, nav: true},
						768:{items: 2, dots: true, nav: true},
						1024:{items: owlSlideNumber, dots: true, nav: true},
						1025:{items: owlSlideNumber, dots: true, nav: true}
				}
		});        
});
$( ".subscription_form" ).on( "submit", function( event ) {
				event.preventDefault();
				var frmData=$( this ).serialize();
				jQuery.ajax({
					type: "POST",
					url: site_url+'/news-letter',
					data: frmData,
					dataType:'json',
					async: false,
					success: function(data) {
						for (var key in data) {
							if(key=='error'){
								$('.subscription_form .error').html(data[key]);
							}else{
								$('.subscription_form .error').html('');
								$('.success').html('');
								$('.success').append('<label class="success">'+data[key]+'</label>');
							}
						}
					}
				});
			});