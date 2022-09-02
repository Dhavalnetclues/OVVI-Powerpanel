var owlNavTextPrev  = '&#8249;';
var owlNavTextNext  = '&#8250;';
$(document).ready(function() {
	/* Owl Next Prev S */
	    function owlNextPrev() { 
	        $(".owl-next").attr({ "title" : "Next" });
	        $(".owl-prev").attr({ "title" : "Previous" });
	    } 
	    setTimeout(owlNextPrev, 100);
	/* Owl Next Prev S */
});