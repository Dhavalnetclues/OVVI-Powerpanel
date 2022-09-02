/* Back to Top Scroll S */
$(window).scroll(function() {
    if ($(this).scrollTop()) {
        $('#back-top').fadeIn();
    } else {
        $('#back-top').fadeOut();
        $('body').removeClass("back-top-scroll")
    }
});
$("#back-top").click(function () {
    $('body').addClass("back-top-scroll")
    $("html, body").animate({scrollTop: 0}, 1000);
});
/* Back to Top Scroll E */