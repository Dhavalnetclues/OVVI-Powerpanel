/* Loader S */
    $(window).on('load',function() {    
      $('.ac-loader').addClass('ac-loader__up');
    });
/* Loader E */

$(window).on('load',function() {
    $('#wrapper').css('opacity','1');
});

/* AOS Animations S */
    function aosFunction() {
        AOS.init({
            disable: 'mobile',
            startEvent: 'DOMContentLoaded',
            initClassName: 'aos-init', 
            animatedClassName: 'aos-animate',
            useClassNames: false, 
            disableMutationObserver: false, 
            debounceDelay: 50, 
            throttleDelay: 99, 
            offset: 120,
            delay: 100,
            duration: 1000,
            easing: 'ease-in-out-quad',
            once: true,
            mirror: false,
            anchorPlacement: 'top-bottom',
        });
    }

    $(window).on('load', function () {
        setTimeout(function () {
            var aos = new TimelineMax();
            aos.call(aosFunction);
        }, 1);
    });
/* AOS Animations E */

/* Common Js Function S */
    /*jQuery.browser S*/
        (function() {(jQuery.browser = jQuery.browser || {}).mobile = (/android|webos|iphone|ipad|ipod|blackberry/i.test(navigator.userAgent.toLowerCase())); }
        (navigator.userAgent || navigator.vendor || window.opera));
    /*jQuery.browser E*/

    /* Table div Wrap S */
        $(document).ready(function() {
    	    $('.cms table').wrap('<div class="table-responsive"></div>');
        });
    /* Table div Wrap E */
/* Common Js Function E */

/* mCustom Scrollbar S */
    (function($){
        $(window).on("load",function(){
            $(".mCcontent").mCustomScrollbar({
                autoHideScrollbar: true,
            });
        });
    })(jQuery);

    (function($){
        $(window).on("load",function(){            
            $(".mCcontentx").mCustomScrollbar({
                axis:"x",
                autoHideScrollbar: true,
            });            
        });
    })(jQuery);
/* mCustom Scrollbar E */

/* CMS Convert plain text email to clickable link S */
    /* $(".cms").filter(function(){
        var html = $(this).html();
        var emailPattern = /[a-zA-Z0-9._-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,4}/g;  
        var matched_str = $(this).html().match(emailPattern);
        if(matched_str){
            var text = $(this).html();
            $.each(matched_str, function(index, value){
                text = text.replace(value,"<a href='mailto:"+value+"' title="+value+" target='_blank'>"+value+"</a>");
            });
            $(this).html(text);
            return $(this)
        }
    }) */
/* CMS Convert plain text email to clickable link E */


/* Thumbnail Container Thumb Generator S */
    $(document).ready(function() {
        $(".ac-webp, .thumbnail-container").each(function() {
            var dataThum = $( this ).attr("data-thumb");
            $( this ).css("padding-bottom", dataThum);
        });
    });
/* Thumbnail Container Thumb Generator E */


$(document).ready(function() {
    $(".cms li").wrapInner("<span></span>");
});


/* Filter Open Function S */
    function openNav1() {
        document.getElementById("menu1").style.right = "0";
        $('#menu__open1').attr( 'onclick', 'closeNav1()' );
        $("#menu__open1.short-menu").addClass('short-menu1');
        $(".inner-page-gap").css("z-index", "2");
        $("body").addClass('menu_overlap1');
    }
/* Filter Open Function E */

/* Filter Close Function S */
    function closeNav1() {
        document.getElementById("menu1").style.right = "-320px";
        $('#menu__open1').attr( 'onclick', 'openNav1()' );
        $("#menu__open1.short-menu").removeClass('short-menu1');
        $(".inner-page-gap").css("z-index", "");
        $("body").removeClass('menu_overlap1');
    }
/* Filter Close Function E */

/* Raw Html Embed Div Remove S */
    $(document).ready(function() {
        $(".raw-html-embed").each(function() {
            var rawHtmlEmbed = $( this ).html();
            $(this).replaceWith(rawHtmlEmbed);
        });
    });
/* Raw Html Embed Div Remove E */

$(document).ready(function() {
    var activeElement = $("#accordianMenu").find('li a.active');
    $(activeElement).parent('li').parents('ul').collapse();
});