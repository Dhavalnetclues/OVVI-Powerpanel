/* Home Banner S */
	$(document).ready(function() {
		$('.slider-for').slick({
			asNavFor: '.slider-nav',
			dots: false,
			arrows: false,
			fade: true,
			slidesToShow: 1,
			slidesToScroll: 1,
			infinite: false,
			autoplay: true,
			autoplaySpeed: 2000,
		});
		$('.slider-nav').slick({
			asNavFor: '.slider-for',
			dots: false,
			arrows: false,
			focusOnSelect: true,
			slidesToShow: 4,
			slidesToScroll: 4,
			infinite: false,
			responsive: [
			    {
			      	breakpoint: 1024,
			      	settings: {
				        slidesToShow: 3,
				        slidesToScroll: 1,
			      	}
			    },
			    {
			      	breakpoint: 768,
			      	settings: {
				        slidesToShow: 2,
				        slidesToScroll: 1,
			      	}
			    },
			],
		});
	});
/* Home Banner E */

/* Service S */
	$(window).on('load',function() {
		var owlClass = '.home-service-slider';
		/* OwlCarousel2 Basic S */
		    $(owlClass + ' .owl-carousel').owlCarousel({
		        loop:false,
				rewind:true,
				margin:30,
		        /* Show next/prev buttons & dots S */
		            nav:false,
		            //navText: [owlNavTextPrev,owlNavTextNext],
		            dots:true,
		            dotsEach:false,
		        /* Show next/prev buttons & dots E*/
		        /* Autoplay S */
		            autoplay:true,
		            autoplayTimeout:5000,
		            autoplayHoverPause:true,
		            smartSpeed: 250,
		        /* Autoplay E */
		        /* Auto Height S */
		            autoHeight:false,
		        /* Auto Height E */
		        /* Lazy Load S */
		            lazyLoad:true,
		            lazyLoadEager:1,
		        /* Lazy Load E */
		        /* Responsive S */
		            responsiveClass:true,
		            responsive:{
		                0:{
		                    items:1,
		                    /* loop:($(owlClass + " .owl-carousel .item").length <= 1 ? false : true) */
		                },
		                480:{
		                    items:2,
		                    /* loop:($(owlClass + " .owl-carousel .item").length <= 1 ? false : true) */
		                },
		                992:{
		                    items:3,
		                    /* loop:($(owlClass + " .owl-carousel .item").length <= 1 ? false : true) */
		                },
		                1441:{
		                    items:4,
		                    /* loop:($(owlClass + " .owl-carousel .item").length <= 1 ? false : true) */
		                }
		            },
		        /* Responsive E */
		        /* Mouse & Touch drag enabled / disabled S */
		            mouseDrag:true,
		            touchDrag:true,
		        /* Mouse & Touch drag enabled / disabled E */
		        /* Padding left and right on stage S */
		            stagePadding:0,
		        /* Padding left and right on stage E */
		    });
		/* OwlCarousel2 Basic E */
	});
/* Service E */

/* Short Service S */
	$(window).on('load',function() {
		var owlClass = '.home-short-service-slider';
		/* OwlCarousel2 Basic S */
		    $(owlClass + ' .owl-carousel').owlCarousel({
		        loop:false,
				rewind:true,
				margin:15,
		        /* Show next/prev buttons & dots S */
		            nav:false,
		            //navText: [owlNavTextPrev,owlNavTextNext],
		            dots:true,
		            dotsEach:false,
		        /* Show next/prev buttons & dots E*/
		        /* Autoplay S */
		            autoplay:true,
		            autoplayTimeout:3000,
		            autoplayHoverPause:true,
		            smartSpeed: 250,
		        /* Autoplay E */
		        /* Auto Height S */
		            autoHeight:false,
		        /* Auto Height E */
		        /* Lazy Load S */
		            lazyLoad:true,
		            lazyLoadEager:1,
		        /* Lazy Load E */
		        /* Responsive S */
		            responsiveClass:true,
		            responsive:{
		                0:{
		                    items:2,
		                    loop:($(owlClass + " .owl-carousel .item").length <= 2 ? false : true)
		                },
		                480:{
		                    items:2,
		                    loop:($(owlClass + " .owl-carousel .item").length <= 2 ? false : true)
		                },
		                768:{
		                    items:3,
		                    loop:($(owlClass + " .owl-carousel .item").length <= 3 ? false : true)
		                },
		                992:{
		                    items:4,
		                    loop:($(owlClass + " .owl-carousel .item").length <= 4 ? false : true)
		                },
		                1025:{
		                    items:5,
		                    loop:($(owlClass + " .owl-carousel .item").length <= 5 ? false : true)
		                },
		                1441:{
		                    items:6,
		                    loop:($(owlClass + " .owl-carousel .item").length <= 6 ? false : true)
		                }
		            },
		        /* Responsive E */
		        /* Mouse & Touch drag enabled / disabled S */
		            mouseDrag:true,
		            touchDrag:true,
		        /* Mouse & Touch drag enabled / disabled E */
		        /* Padding left and right on stage S */
		            stagePadding:0,
		        /* Padding left and right on stage E */
		    });
		/* OwlCarousel2 Basic E */
	});
/* Short Service E */

/* Notification S */
	$(window).on('load',function() {
		var owlClass = '.n-item-m';
		/* OwlCarousel2 Basic S */
		    $(owlClass + ' .owl-carousel').owlCarousel({
		        loop:false,
				rewind:true,
				margin:15,
		        /* Show next/prev buttons & dots S */
		            nav:false,
		            //navText: [owlNavTextPrev,owlNavTextNext],
		            dots:false,
		            dotsEach:false,
		        /* Show next/prev buttons & dots E*/
		        /* Autoplay S */
		            autoplay:true,
		            autoplayTimeout:3000,
		            autoplayHoverPause:true,
		            smartSpeed: 250,
		        /* Autoplay E */
		        /* Auto Height S */
		            autoHeight:false,
		        /* Auto Height E */
		        /* Lazy Load S */
		            lazyLoad:true,
		            lazyLoadEager:1,
		        /* Lazy Load E */
		        /* Responsive S */
		            responsiveClass:true,
		            responsive:{
		                0:{
		                    items:1,
		                },
		            },
		        /* Responsive E */
		        /* Mouse & Touch drag enabled / disabled S */
		            mouseDrag:false,
		            touchDrag:false,
		        /* Mouse & Touch drag enabled / disabled E */
		        /* Padding left and right on stage S */
		            stagePadding:0,
		        /* Padding left and right on stage E */
		    });
		/* OwlCarousel2 Basic E */
	});
/* Notification E */

/* Subscribe & Stay Updated E */
	
/* Subscribe & Stay Updated E */




$(window).on('load',function() {
    $('#myModal').modal('show');
});