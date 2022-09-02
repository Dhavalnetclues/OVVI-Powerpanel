/* Loader S */
$(window).on('load', function() {
    $('.ac-loader').addClass('ac-loader__up');
    insertPageHits();
});
/* Loader E */

$(window).on('load', function() {
    $('#wrapper').css('opacity', '1');
    var owlClass = '.home-banner';
    $(owlClass + ' .owl-carousel').owlCarousel({
        loop: false,
        rewind: true,
        margin: 0,
        /* Show next/prev buttons & dots S */
        nav: false,
        //navText: [owlNavTextPrev,owlNavTextNext],
        dots: false,
        dotsEach: false,
        /* Show next/prev buttons & dots E*/
        /* Autoplay S */
        autoplay: true,
        autoplayTimeout: 5000,
        autoplayHoverPause: true,
        smartSpeed: 250,
        /* Autoplay E */
        /* Auto Height S */
        autoHeight: false,
        /* Auto Height E */
        /* Lazy Load S */
        lazyLoad: true,
        lazyLoadEager: 1,
        /* Lazy Load E */
        /* Responsive S */
        responsiveClass: true,
        responsive: {
            0: {
                items: 1,
                /* loop:($(owlClass + " .owl-carousel .item").length <= 1 ? false : true) */
            },
            480: {
                items: 1,
                /* loop:($(owlClass + " .owl-carousel .item").length <= 1 ? false : true) */
            },
            992: {
                items: 1,
                /* loop:($(owlClass + " .owl-carousel .item").length <= 1 ? false : true) */
            },
            1441: {
                items: 1,
                /* loop:($(owlClass + " .owl-carousel .item").length <= 1 ? false : true) */
            }
        },
        /* Responsive E */
        /* Mouse & Touch drag enabled / disabled S */
        mouseDrag: true,
        touchDrag: true,
        /* Mouse & Touch drag enabled / disabled E */
        /* Padding left and right on stage S */
        stagePadding: 0,
        /* Padding left and right on stage E */
    });
});
/* SYNC OwlCarousel2 S */
$(document).ready(function() {
    var sync1 = $("#sync1");
    var sync2 = $("#sync2");
    var slidesPerPage = 4;
    var syncedSecondary = true;

    sync1.owlCarousel({
        items: 1,
        slideSpeed: 2000,
        nav: false,
        autoplay: false,
        dots: false,
        loop: true,
        margin: 5,
        responsiveRefreshRate: 200,
    }).on('changed.owl.carousel', syncPosition);

    sync2.on('initialized.owl.carousel', function() {
            sync2.find(".owl-item").eq(0).addClass("current");
        })
        .owlCarousel({
            items: slidesPerPage,
            dots: false,
            nav: false,
            smartSpeed: 200,
            slideSpeed: 500,
            slideBy: slidesPerPage,
            responsiveRefreshRate: 100
        }).on('changed.owl.carousel', syncPosition2);

    function syncPosition(el) {
        var count = el.item.count - 1;
        var current = Math.round(el.item.index - (el.item.count / 2) - .5);

        if (current < 0) {
            current = count;
        }
        if (current > count) {
            current = 0;
        }

        sync2
            .find(".owl-item")
            .removeClass("current")
            .eq(current)
            .addClass("current");
        var onscreen = sync2.find('.owl-item.active').length - 1;
        var start = sync2.find('.owl-item.active').first().index();
        var end = sync2.find('.owl-item.active').last().index();

        if (current > end) {
            sync2.data('owl.carousel').to(current, 100, true);
        }
        if (current < start) {
            sync2.data('owl.carousel').to(current - onscreen, 100, true);
        }
    }

    function syncPosition2(el) {
        if (syncedSecondary) {
            var number = el.item.index;
            sync1.data('owl.carousel').to(number, 100, true);
        }
    }

    sync2.on("click", ".owl-item", function(e) {
        e.preventDefault();
        var number = $(this).index();
        sync1.data('owl.carousel').to(number, 300, true);
    });
});
/* SYNC OwlCarousel2 E */
/* SYNC2 OwlCarousel2 S */
$(document).ready(function() {
    var sync3 = $("#sync3");
    var sync4 = $("#sync4");
    var slidesPerPage = 4;
    var syncedSecondary = true;

    sync3.owlCarousel({
        items: 1,
        slideSpeed: 2000,
        nav: false,
        autoplay: true,
        dots: false,
        loop: true,
        margin: 5,
        responsiveRefreshRate: 200,
    }).on('changed.owl.carousel', syncPosition);

    sync4.on('initialized.owl.carousel', function() {
            sync4.find(".owl-item").eq(0).addClass("current");
        })
        .owlCarousel({
            items: slidesPerPage,
            dots: false,
            nav: false,
            smartSpeed: 200,
            slideSpeed: 500,
            slideBy: slidesPerPage,
            responsiveRefreshRate: 100
        }).on('changed.owl.carousel', syncPosition2);

    function syncPosition(el) {
        var count = el.item.count - 1;
        var current = Math.round(el.item.index - (el.item.count / 2) - .5);

        if (current < 0) {
            current = count;
        }
        if (current > count) {
            current = 0;
        }

        sync4
            .find(".owl-item")
            .removeClass("current")
            .eq(current)
            .addClass("current");
        var onscreen = sync4.find('.owl-item.active').length - 1;
        var start = sync4.find('.owl-item.active').first().index();
        var end = sync4.find('.owl-item.active').last().index();

        if (current > end) {
            sync4.data('owl.carousel').to(current, 100, true);
        }
        if (current < start) {
            sync4.data('owl.carousel').to(current - onscreen, 100, true);
        }
    }

    function syncPosition2(el) {
        if (syncedSecondary) {
            var number = el.item.index;
            sync3.data('owl.carousel').to(number, 100, true);
        }
    }

    sync4.on("click", ".owl-item", function(e) {
        e.preventDefault();
        var number = $(this).index();
        sync3.data('owl.carousel').to(number, 300, true);
    });
});
/* SYNC OwlCarousel2 E */
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
/* AOS Animations E */

/* Common Js Function S */
/*jQuery.browser S*/
(function() {
        (jQuery.browser = jQuery.browser || {}).mobile = (/android|webos|iphone|ipad|ipod|blackberry/i.test(navigator.userAgent.toLowerCase()));
    }
    (navigator.userAgent || navigator.vendor || window.opera));
/*jQuery.browser E*/

/* Table div Wrap S */
$(document).ready(function() {
    $('.cms table').wrap('<div class="table-responsive"></div>');
});
/* Table div Wrap E */
/* Common Js Function E */

/* mCustom Scrollbar S */
(function($) {
    $(window).on("load", function() {
        $(".mCcontent").mCustomScrollbar({
            autoHideScrollbar: true,
        });
    });
})(jQuery);

(function($) {
    $(window).on("load", function() {
        $(".mCcontentx").mCustomScrollbar({
            axis: "x",
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
function dataThum(e) {
    $(e).each(function() {
        var dataThum = $(this).attr("data-thumb");
        $(this).css("padding-bottom", dataThum);
    });
}
dataThum(".ac-webp, .thumbnail-container");
/* Thumbnail Container Thumb Generator E */


$(document).ready(function() {
    $(".cms li").wrapInner("<span></span>");
    $('a.scroll_down').click(function() {
        $root.animate({ scrollTop: $($(this).attr('href')).offset().top - 0 }, 2000);
        return false;
    });
});


/* Filter Open Function S */
function openNav1() {
    document.getElementById("menu1").style.right = "0";
    $('#menu__open1').attr('onclick', 'closeNav1()');
    $("#menu__open1.short-menu").addClass('short-menu1');
    $(".inner-page-gap").css("z-index", "2");
    $("body").addClass('menu_overlap1');
}
/* Filter Open Function E */

/* Filter Close Function S */
function closeNav1() {
    document.getElementById("menu1").style.right = "-320px";
    $('#menu__open1').attr('onclick', 'openNav1()');
    $("#menu__open1.short-menu").removeClass('short-menu1');
    $(".inner-page-gap").css("z-index", "");
    $("body").removeClass('menu_overlap1');
}
/* Filter Close Function E */

/* Raw Html Embed Div Remove S */
$(document).ready(function() {
    var segArr = JSON.parse(segments.replace(/&quot;/g, '"'));
    if (segArr[0] != 'survey') {
        $(".raw-html-embed").each(function() {
            var rawHtmlEmbed = $(this).html();
            $(this).replaceWith(rawHtmlEmbed);
        });
    }
});
/* Raw Html Embed Div Remove E */

$(document).ready(function() {
    var activeElement = $("#accordianMenu").find('li a.active');
    $(activeElement).parent('li').parents('ul').collapse();
    $(activeElement).parent('li').parents('ul').parent('li').children('a').addClass('active');

    var accordianMenu = $("#full-menu-clone").find('li.active');
    if (accordianMenu.length > 0) {
        $(accordianMenu).each(function(key, value) {
            $(value).parent('ul').prev('span').addClass('is-close');
        });
    }

    var hedaerActiveElement = $("#headerMenu").find('li.active');
    if (hedaerActiveElement.length > 0) {
        $(hedaerActiveElement).each(function(key, value) {
            $(value).parents('ul').prev('span').addClass('is-close');
        });
    }
    $(hedaerActiveElement).parents('ul').parent('li').addClass('active');


    var navigationMenuActiveElement = $(".navigationMenu").find('li.active');
    $(navigationMenuActiveElement).parent('ul').collapse();
    $(navigationMenuActiveElement).parents('ul').parent('li').addClass('active');

});


function insertPageHits() {

    if (segments.length > 0) {
        segments = JSON.parse(segments.replace(/&quot;/g, '"'));
    }
    $.ajax({
        type: "POST",
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        url: site_url + '/insert-page-hits',
        data: {
            'segments': segments
        },
        error: function(xhr, ajaxOptions, thrownError) {
            console.log('Oops!!! Something went wrong');
        }
    });

}

$(document).ready(function() {
    $('#cookie_policy').click(function() {
        $.ajax({
            url: site_url + "/accept-privacy",
            type: "POST",
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            success: function(data) {
                $('.ac-cookies').css('display', 'none');
            }
        });
    });
});


/* AOS */
$(window).on('load refresh resize', function() {
    AOS.init();
    AOS.refresh();
    setTimeout(AOS.refreshHard, 150);
});
$(document).ready(function() {
    AOS.init({
        once: true,
        duration: 900,
        disable: 'mobile',
        offset: 90,
        startEvent: 'DOMContentLoaded',
        delay: 0,
    });
    $(window).on('load', function() {
        AOS.refresh();
    });
    $(window).on("blur", function() {
        AOS.refresh();
    });
});
/* AOS End */