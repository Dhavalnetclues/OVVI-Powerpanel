/* Common Js Function S */



	/* Wrapper S */

	/*

        $(window).on('load',function() {    

            $('#wrapper').css('opacity','1');

		});

	*/	

    /* Wrapper E */



    /*jQuery.browser S*/

        (function() {(jQuery.browser = jQuery.browser || {}).mobile = (/android|webos|iphone|ipad|ipod|blackberry/i.test(navigator.userAgent.toLowerCase())); }

        (navigator.userAgent || navigator.vendor || window.opera));

    /*jQuery.browser E*/

data-bs-toggle="tooltip"

    /* Owl Next Prev S */

        function owlNextPrev() { 

            $(".owl-next").attr({ "title" : "Next" });

            $(".owl-prev").attr({ "title" : "Previous" });

        } 

        setTimeout(owlNextPrev, 50);

    /* Owl Next Prev S */



    /* Table div Wrap S */

        $(document).ready(function() {

		   $('.cms table').wrap('<div class="table-responsive"></div>');

		   $('.events-calender .fc-view-container').wrap('<div class="table-responsive"></div>');		   



		   $('[data-toggle="tooltip"]').tooltip(); 

        });

    /* Table div Wrap E */



    /* SVGConverter function S */

        function SVGConverter (e) {

            $(e + ' img.svg').each(function() {

                var $img = $(this),

                    imgID = $img.attr('id'),

                    imgClass = $img.attr('class'),

                    imgURL = $img.attr('src');



                $.get(imgURL, function(data) {

                    var $svg = $(data).find('svg'), $svg = $svg.removeAttr('xmlns:a');

                    if(typeof imgID !== 'undefined') { $svg = $svg.attr('id', imgID); }

                    if(typeof imgClass !== 'undefined') { $svg = $svg.attr('class', imgClass+' replaced-svg'); }

                    if(!$svg.attr('viewBox') && $svg.attr('height') && $svg.attr('width')) {

                        $svg.attr('viewBox', '0 0 ' + $svg.attr('height') + ' ' + $svg.attr('width'));

                    }

                    $img.replaceWith($svg);    

                }, 'xml');    

            });

        }

        SVGConverter (".svg-img");

    /* SVGConverter function E */



/* Common Js Function E */



/*Animation S*/

function animated() {

    $('.animated').not('.load').each(function(i) {

        var $this = $(this);

        var ind = i * 100;

        var docViewTop = $(window).scrollTop();

        var docViewBottom = docViewTop + $(window).height();

        var elemTop = $this.offset().top;

        if (docViewBottom >= elemTop) {

            setTimeout(function() {

                $this.addClass('load');

                $this.trigger('animated');

            }, ind);

        }

    });

}

$(document).ready(function($) {

    animated();

});

$(window).scroll(function() {

    animated();

});

/* Animation E */





/* -------------------------------------------------------------

|   If you have to use any js function then please put below.   |

--------------------------------------------------------------*/



// Header Fixed

if ($(window).width() > 1024) {

$(window).scroll(function(){

	if ($(window).scrollTop() >= 220) {

	   $('header').addClass('fixed');	   

	}

	else {

	   $('header').removeClass('fixed');	   

	}

}); 

}  



// Mega Menu

if($(window).width() > 1025){

	var sameheight = $('.menu ul .mega-menu').height();

	$('.menu ul .mega-menu .mega-border').css({'min-height':sameheight});

}





// OWL Title Alt

$(".owl-nav").children(".owl-prev").attr('title', 'Previous');

$(".owl-nav").children(".owl-next").attr('title', 'Next');



$(document).ready(function(){

	// Alert slider	

	$('.alert_slide').owlCarousel({		

		items:1,

		loop:true,		

		dots:false,

		nav:true,

		autoplay:false,

		navText : ['<i class="fa fa-angle-left" aria-hidden="true"></i>','<i class="fa fa-angle-right" aria-hidden="true"></i>'],

		smartSpeed:1500,

		responsive:{

			0:{

				items:1,						

			},					

			568:{

				items:1,		

			},					

			1000:{

				items:1,

			}		

		}

	});		



	





	// Blog slider		

	$('.blog_slide .owl-carousel').owlCarousel({		

		items:3,

		loop:false,

		margin:0,

		dots:true,

		autoplay:true,			

		responsive: {

			0:{

				items:1,						

			},					

			568:{

				items:2,		

			},

			767:{

				items:2,

			},					

			1000:{

				items:3,

			}		

		}

	});		

	

	

});



// FAQ's

function toggleIcon(e) {

	$(e.target)

	  .prev(".panel-heading")

	  .find(".more-less")

	  .toggleClass("icon-plus icon-minus");

}

$(".panel_listing.panel-group").on("hidden.bs.collapse", toggleIcon);

$(".panel_listing.panel-group").on("shown.bs.collapse", toggleIcon);





// Model issue	

( function($) {

	function iframeModalOpen(){

		$('.modal_notes .close').on('click', function(e) {

			var src = $(this).attr('data-src');

			var width = $(this).attr('data-width') || 640; 

			var height = $(this).attr('data-height') || 360;



			var allowfullscreen = $(this).attr('data-video-fullscreen');



			$(".modal_notes iframe").attr({

				'src': src,

				'height': height,

				'width': width,

				'allowfullscreen':''

			});

		});

		$('.modal_notes').on('hidden.bs.modal', function(){

			$(this).find('iframe').html("");

			$(this).find('iframe').attr("src", "");

		});

	}

	$(document).ready(function(){

		iframeModalOpen();

	});

} ) ( jQuery );  









